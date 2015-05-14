class Person
{

    constructor (name)
    {
        this.name = name
    }

    get name()
    {
        return `my name is ${this._name}`
    }

    set name(name)
    {
        //[this.firstName, this.lastName] = name.split(" ")
        this._name = name
    }

    luckNumber()
    {
        const PI = 3.141593
        return PI;
    }

}
 
var boy = new Person("kevin Wu")
boy.name = "Vivian Li"
console.log(boy.name)
console.log(boy)
console.log(boy.luckNumber())

//SyntaxError
