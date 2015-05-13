class Person
{
    constructor(firstName, lastName)
    {
        this.firstName = firstName;
        this.lastName = lastName;
    }
 
    get name()
    {
        return this.firstName + " " + this.lastName;
    }
 
    set name(name)
    {
        var names = name.split(" ");
        this.firstName = names[0];
        this.lastName = names[1];
    }
}
 
var boy = new Person("Bo-Yi", "Wu");
boy.name = "Boy Apple";
console.log(boy.name);