@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Patnet</h1>
    <a href="{{ route('partners.create') }}" class="bg-blue-600 text-white px-3 py-1 inline-block mb-3">Tambah Patnet</a>
    <table class="w-full border">
        <thead><tr><th class="border p-2">Nama</th></tr></thead>
        <tbody>
        @foreach($partners as $partner)
            <tr>
                <td class="border p-2"><a href="{{ route('partners.edit', $partner) }}">{{ $partner->name }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $partners->links() }}
    <p class="text-sm text-gray-500 mt-4">Placeholder list. Form create/edit belum lengkap.</p>
@endsection


