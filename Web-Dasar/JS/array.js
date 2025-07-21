let hobbies = ['main game', 'lari', 'ngoding'];

console.log(hobbies);
console.log(hobbies[0]);
console.log(hobbies[1]);
console.log(hobbies[2]);

hobbies.unshift('');
hobbies.unshift('');

hobbies.pop('');
hobbies.pop('');

hobbies.shift('');
hobbies.shift('');

hobbies.push('');
hobbies.push('');

hobbies.forEach((hobby, index) => {
    document.writeln(`${index + 1}, ${hobby} <br/>`);
});