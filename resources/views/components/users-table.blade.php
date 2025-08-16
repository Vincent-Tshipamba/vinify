@props(['users', 'roles'])
<div class="card w-full">
    <div class="card-body">
        <div class="table-responsive">
            <table border="1" id="users-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="dark:bg-neutral-800">
                            <span class="flex items-center">
                                #
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th class="dark:bg-neutral-800">
                            <span class="flex items-center">
                                Nom d'utilisateur
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th class="dark:bg-neutral-800">
                            <span class="flex items-center">
                                Email
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th class="dark:bg-neutral-800">
                            <span class="flex items-center">
                                Role
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th class="dark:bg-neutral-800">
                            <span class="flex items-center">
                                Statut
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th class="dark:bg-neutral-800">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        @php
                            $cache_exists = false;
                            if (Cache::has('user-is-online-' . $user->id)) {
                                $cache_exists = true;
                            }
                        @endphp
                        <tr
                            class="hover:bg-[#f0e6d9] dark:hover:bg-gray-800 hover:scale-100 transition-all duration-300 ease-in-out">
                            <td>{{ $key + 1 }}</td>

                            @php
                                $isNavigable = $user->client || $user->seller;
                            @endphp

                            <td class="size-px whitespace-nowrap"
                                onclick="@if ($isNavigable) window.location.href='{{ route('admin.users.show', $user->id) }}'
                                     @else
                                        showUserProfile({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->created_at }}', '{{ $user->last_activity }}', '{{ $cache_exists }}') @endif">

                                <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                    <div class="flex items-center gap-x-3">
                                        <img class="inline-block size-9.5 rounded-full"
                                            src="{{ $user->client?->image ? asset($user->client->image) : 'https://images.unsplash.com/photo-1531927557220-a9e23c1e4794?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80' }}"
                                            alt="Avatar">
                                        <div class="grow">
                                            <span
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                                {{ $user->name }}
                                            </span>
                                            <span class="block text-sm text-gray-500 dark:text-neutral-500">
                                                {{ $user->email }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="h-px w-72 whitespace-nowrap">
                                <div class="px-6 py-3">
                                    <span
                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">Director</span>
                                    <span class="block text-sm text-gray-500 dark:text-neutral-500">Human
                                        resources</span>
                                </div>
                            </td>

                            <td class="">
                                @foreach ($roles as $role)
                                    {{ $user->roles->contains($role) ? $role->name : '' }}
                                @endforeach
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    @if ($cache_exists)
                                        <span
                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417
                                                    5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75
                                                    0 0 0-.01-1.05z" />
                                            </svg>
                                            En ligne
                                        </span>
                                    @else
                                        <span
                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.646 4.646a.5.5 0 0 1 .708 0L8
                                                    7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5
                                                    0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293
                                                    8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                            </svg>
                                            Inactif
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer"
                                        onchange="changeUserStatus({{ $user->id }})"
                                        {{ $user->is_active ? 'checked' : '' }}>
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#e38407]">
                                    </div>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>