@foreach($users as $user)
    <x-user-card :user="$user" statLabel="Followers" :statValue="$user->followers()->count()" />
@endforeach