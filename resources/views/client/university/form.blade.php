@extends('client.layouts.app')

@section('content')
<div id="app">
    <multi-step-form></multi-step-form>
</div>

<script src="{{ mix('js/app.js') }}"></script>
@endsection
