// Create a new isolate limited to 128MB
const process = require('process');
const fs = require('fs')

let path = process.argv[2];
if(path != undefined && !path.startsWith('--path')){
    throw new Error('Invalid path');
}

path = path.replace('--path=', '');
console.log(path)

const hostileCode = fs.readFileSync(path, 'utf8')

console.log(hostileCode)

const ivm = require('isolated-vm');
const isolate = new ivm.Isolate({ memoryLimit: 128 });

const { default: axios } = require('axios');

// Create a new context within this isolate. Each context has its own copy of all the builtin
// Objects. So for instance if one context does Object.prototype.foo = 1 this would not affect any
// other contexts.
const context = isolate.createContextSync();

// Get a Reference{} to the global object within the context.
const jail = context.global;

// This makes the global object available in the context as `global`. We use `derefInto()` here
// because otherwise `global` would actually be a Reference{} object in the new isolate.
jail.setSync('global', jail.derefInto());

// Returns a promise with the returned value of the function. The returned value must not be a function or promise.

const getCallback = async function (...args) {
    let response = await axios.get(...args);    
    return response.data;
};
context.evalClosureSync(
    `global.get = function(...args) {
    return $0.applySync(undefined, args, { arguments: { copy: true }, result: { copy: true, promise: true } });
    }`,
    [getCallback],
    { arguments: { reference: true } }
);


const postCallback = async function (...args) {
    let response = await axios.post(...args);    
    return response.data;
};
context.evalClosureSync(
    `global.post = function(...args) {
    return $0.applySync(undefined, args, { arguments: { copy: true }, result: { copy: true, promise: true } });
    }`,
    [postCallback],
    { arguments: { reference: true } }
);


// jail.set('http', http.request)
// We will create a basic `log` function for the new isolate to use.
jail.setSync('log', function(...args) {
	console.log("[LOG]: ",...args);
});

// And let's test it out:
context.evalSync('log("Begin code execution")');

// Let's see what happens when we try to blow the isolate's memory
const hostile = isolate.compileScriptSync(hostileCode);

// Using the async version of `run` so that calls to `log` will get to the main node isolate
hostile.run(context)
.then((res) => {
    console.log("[LOG]: End code execution");
})
.catch(err => console.error(err));
