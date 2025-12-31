@extends('layouts.chef_layout')

@section('title', 'Chef De Projet | Dashboard Home')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}</h1>
    <p>This is your Chef de Projet dashboard home page.</p>
    <h1>CHEF DE PROJET</h1>

@endsection
