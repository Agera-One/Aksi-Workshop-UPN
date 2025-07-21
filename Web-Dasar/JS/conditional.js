let nilai = 76;
const kkm = 75;
let NilaiJuara = 96;

if (nilai < kkm) {
    console.log('SELAMAT ANDA JUARA KELAS');
} else {
    console.log('ANDA HARUS REMIDI');
}

if (nilai > kkm) {
    console.log('SELAMAT ANDA JUARA KELAS');
} else {
    console.log('ANDA HARUS REMIDI');
}

if (nilai <= kkm) {
    console.log('SELAMAT ANDA JUARA KELAS');
} else {
    console.log('ANDA HARUS REMIDI');
}

if (nilai >= kkm) {
    console.log('SELAMAT ANDA JUARA KELAS');
} else {
    console.log('ANDA HARUS REMIDI');
}

if (nilai >= NilaiJuara) {
    console.log('SELAMAT ANDA JUARA KELAS');
} else if (nilai >= kkm) {
    console.log('ANDA AMAN');
} else {
    console.log('ANDA HARUS REMIDI');
}






let nama1 = 'John Doe';
let nama2 = 'Jane Doe';

if (nama1 == nama2) {
    console.log('YA NAMA MEREKA SAMA');
} else {
    console.log('MEREKA BEDA ORANG');
}



let nama3 = 'John Doe';
let nama4 = 'john doe';

if (nama3.toLowerCase() == nama4) {
    console.log('YA NAMA MEREKA SAMA');
} else {
    console.log('MEREKA BEDA ORANG');
}



let bill1 = '10';
let bill2 = 10;

if (bill1 == bill2) {
    console.log('SAMA');
} else {
    console.log('BEDA');
}

if (bill1 === bill2) {
    console.log('SAMA');
} else {
    console.log('BEDA');
}