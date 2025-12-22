let a = {
    name: 'Arnav',
    dob: '2004-03-23',
    friends: ['Laukik','Hitansh']
}

console.log(a.dob)

let friends = a.friends
friends.forEach(f => {
    console.log('Hi ' + f)
});