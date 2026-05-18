<?php

use App\Livewire\Admin\Matangazo;
use App\Livewire\Shared\AnnouncementModal;
use App\Models\SiteAnnouncement;
use App\Models\User;
use Livewire\Livewire;

test('admin can create a multi-audience announcement', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(Matangazo::class)
        ->call('create')
        ->set('title', 'Mfumo Mpya')
        ->set('body', 'Tumeshusha bei za subscriptions kwa 20%.')
        ->set('type', 'success')
        ->set('audiences.public', true)
        ->set('audiences.mteja', true)
        ->set('audiences.winga', false)
        ->call('save')
        ->assertSet('showModal', false);

    $a = SiteAnnouncement::firstOrFail();
    expect($a->title)->toBe('Mfumo Mpya')
        ->and($a->audiences)->toEqual(['public', 'mteja'])
        ->and($a->type)->toBe('success')
        ->and($a->is_active)->toBeTrue()
        ->and($a->created_by)->toBe($admin->id);
});

test('save fails when no audience is picked', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(Matangazo::class)
        ->call('create')
        ->set('title', 'Test')
        ->set('body', 'Body')
        ->call('save')
        ->assertHasErrors('audiences');

    expect(SiteAnnouncement::count())->toBe(0);
});

test('toggle and delete work', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $a = SiteAnnouncement::factory()->create(['is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Matangazo::class)
        ->call('toggleActive', $a->id);

    expect($a->fresh()->is_active)->toBeFalse();

    Livewire::actingAs($admin)
        ->test(Matangazo::class)
        ->call('delete', $a->id);

    expect(SiteAnnouncement::find($a->id))->toBeNull();
});

test('active scope filters out inactive, expired and scheduled', function () {
    $live = SiteAnnouncement::factory()->create();
    $inactive = SiteAnnouncement::factory()->inactive()->create();
    $expired = SiteAnnouncement::factory()->expired()->create();
    $scheduled = SiteAnnouncement::factory()->scheduled()->create();

    $active = SiteAnnouncement::active()->pluck('id')->all();

    expect($active)->toContain($live->id)
        ->and($active)->not->toContain($inactive->id)
        ->and($active)->not->toContain($expired->id)
        ->and($active)->not->toContain($scheduled->id);
});

test('forAudience scope only returns announcements targeting that audience', function () {
    SiteAnnouncement::factory()->targeting(['public'])->create(['title' => 'P']);
    SiteAnnouncement::factory()->targeting(['mteja'])->create(['title' => 'M']);
    SiteAnnouncement::factory()->targeting(['winga', 'mteja'])->create(['title' => 'WM']);

    $mteja = SiteAnnouncement::forAudience('mteja')->pluck('title')->all();
    $winga = SiteAnnouncement::forAudience('winga')->pluck('title')->all();
    $public = SiteAnnouncement::forAudience('public')->pluck('title')->all();

    expect($mteja)->toEqualCanonicalizing(['M', 'WM'])
        ->and($winga)->toEqual(['WM'])
        ->and($public)->toEqual(['P']);
});

test('dashboard modal shows announcement to mteja and remembers dismissal', function () {
    $mteja = User::factory()->create(['role' => 'mteja']);
    $a = SiteAnnouncement::factory()->targeting(['mteja'])->create(['title' => 'Hi Mteja']);

    $component = Livewire::actingAs($mteja)
        ->test(AnnouncementModal::class, ['scope' => 'mteja'])
        ->assertSet('show', true)
        ->assertSet('announcementId', $a->id);

    expect($a->users()->where('users.id', $mteja->id)->wherePivotNotNull('viewed_at')->exists())->toBeTrue();

    $component->call('dismiss')
        ->assertSet('show', false);

    expect($a->users()->where('users.id', $mteja->id)->wherePivotNotNull('dismissed_at')->exists())->toBeTrue();

    Livewire::actingAs($mteja)
        ->test(AnnouncementModal::class, ['scope' => 'mteja'])
        ->assertSet('show', false);
});

test('dashboard modal ignores announcements targeting other audiences', function () {
    $winga = User::factory()->create(['role' => 'winga']);
    SiteAnnouncement::factory()->targeting(['mteja'])->create();
    SiteAnnouncement::factory()->targeting(['public'])->create();

    Livewire::actingAs($winga)
        ->test(AnnouncementModal::class, ['scope' => 'winga'])
        ->assertSet('show', false);
});

test('non-dismissible announcement cannot be dismissed', function () {
    $mteja = User::factory()->create(['role' => 'mteja']);
    $a = SiteAnnouncement::factory()->targeting(['mteja'])->create(['is_dismissible' => false]);

    Livewire::actingAs($mteja)
        ->test(AnnouncementModal::class, ['scope' => 'mteja'])
        ->assertSet('show', true)
        ->call('dismiss')
        ->assertSet('show', true);

    expect($a->users()->where('users.id', $mteja->id)->wherePivotNotNull('dismissed_at')->exists())->toBeFalse();
});

test('public banner shows on welcome page when active public announcement exists', function () {
    SiteAnnouncement::factory()->targeting(['public'])->create(['title' => 'Karibu PINGA']);

    $this->get('/')
        ->assertStatus(200)
        ->assertSee('Karibu PINGA');
});

test('public banner does not show mteja/winga-only announcements', function () {
    SiteAnnouncement::factory()->targeting(['mteja'])->create(['title' => 'Mteja siri']);
    SiteAnnouncement::factory()->targeting(['winga'])->create(['title' => 'Winga siri']);

    $this->get('/')
        ->assertStatus(200)
        ->assertDontSee('Mteja siri')
        ->assertDontSee('Winga siri');
});
