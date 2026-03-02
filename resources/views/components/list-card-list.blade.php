@foreach($lists as $list)
    <x-list-card :list="$list" />
@endforeach