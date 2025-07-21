fetch('https://pokeapi.co/api/v2/pokemon')
.then(res => res.json())
.then(data => {
    console.log(data)
})

async function getPokemons(params) {
    const res = await fetch('https://pokeapi.co/api/v2/pokemon')

    if (res.ok) {
        const pokemons = await res.json();
        console.log(pokemons);
    } else {
        console.error('DATA TIDAK BISA DIAMBIL');
    }
}
getPokemons();

const containergrid = document.getElementById(container);

function renderPokemons(data) {
    console.log(data)
}
renderPokemons();
