@props(['user' => null])

<div class="avatar">{{ $user?->name ? strtoupper(mb_substr($user->name, 0, 1)) : '?' }}</div>