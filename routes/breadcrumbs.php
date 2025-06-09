<?php // routes/breadcrumbs.php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Beranda
Breadcrumbs::for('Toko', function(BreadcrumbTrail $trail){
    $trail->push('Toko Zakiah', route('dashboard.index'));
});
Breadcrumbs::for('Beranda', function (BreadcrumbTrail $trail) {
    $trail->parent('Toko');
    $trail->push('Beranda', route('dashboard.index'));
});

// Beranda > Profil
Breadcrumbs::for('Profile', function (BreadcrumbTrail $trail) {
    $trail->parent('Beranda');
    $trail->push('Profile', route('dashboard.show'));
});

// Kalkulator
Breadcrumbs::for('Calculator', function (BreadcrumbTrail $trail) {
    $trail->parent('Beranda');
    $trail->push('Kalkulator', route('calculator.index'));
});

// Produk
Breadcrumbs::for('Product', function (BreadcrumbTrail $trail) {
    $trail->parent('Beranda');
    $trail->push('Produk', route('product.index'));
});

// Produk > Add
Breadcrumbs::for('ProductAdd', function (BreadcrumbTrail $trail) {
    $trail->parent('Product');
    $trail->push('Tambah');
});

// Produk > Edit
Breadcrumbs::for('ProductEdit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('Product');
    $trail->push('Edit');
    $trail->push($data[0]->name);
});


// Transaksi
Breadcrumbs::for('Transaction', function (BreadcrumbTrail $trail) {
    $trail->parent('Beranda');
    $trail->push('Transaksi', route('transaction.index'));
});

// Transaksi > Add
Breadcrumbs::for('TransactionAdd', function (BreadcrumbTrail $trail) {
    $trail->parent('Transaction');
    $trail->push('Tambah');
});

// Transaksi > Edit
Breadcrumbs::for('TransactionEdit', function (BreadcrumbTrail $trail, $transaction) {
    $trail->parent('Transaction');
    $trail->push('Edit');
    $trail->push($transaction->date);
});

// User
Breadcrumbs::for('User', function (BreadcrumbTrail $trail) {
    $trail->parent('Beranda');
    $trail->push('User', route('user.index'));
});

// User > Add
Breadcrumbs::for('UserAdd', function (BreadcrumbTrail $trail) {
    $trail->parent('User');
    $trail->push('Tambah');
});

// User > Edit
Breadcrumbs::for('UserEdit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('User');
    $trail->push('Edit');
    $trail->push($data[0]->username);
});
