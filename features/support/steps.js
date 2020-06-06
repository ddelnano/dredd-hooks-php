const os = require('os');
const path = require('path');
const util = require('util');
const childProcess = require('child_process');
const { expect } = require('chai');
const fs = require('fs-extra');
const net = require('net');
const url = require('url');
const which = require('which');
const pidtree = require('pidtree');
const {
  Given,
  When,
  Then,
  Before,
  After,
  AfterAll,
} = require('cucumber');


Before(function hook() {
  this.dir = fs.mkdtempSync(path.join(os.tmpdir(), 'dredd-hooks-template-'));
  this.dreddBin = path.join(process.cwd(), 'node_modules', '.bin', 'dredd');
  process.env.PATH = `${process.env.PATH}:${process.cwd()}/bin`;
  this.env = { ...process.env };
  this.dataSent = '';
});

After(async function hook() {
  // kill all running processes
  const pids = [];
  try {
    pids.push(...await pidtree(process.pid));
  } catch (error) {
    // the process doesn't exist anymore
  }
  pids.forEach((pid) => {
    try {
      process.kill(pid, 'SIGKILL');
    } catch (error) {
      // re-throw except in case it is 'ESRCH' (process cannot be found)
      if (error.code !== 'ESRCH') throw error;
    }
  });
  // remove the temporary directory
  return fs.remove(this.dir);
});


Given('I have Dredd installed', function step() {
  which.sync(this.dreddBin); // throws if not found
});

Given('I have dredd-hooks-php installed', function step() {
  which.sync("dredd-hooks-php"); // throws if not found
});

Given('a file {string} with a server responding on {string} to wildcard hooks', function step(filename, fullURL) {
  const urlParts = url.parse(fullURL);
  const content = `require('http')
  .createServer((req, res) => {
    if (req.method === 'GET') {
      res.writeHead(200);
    } else if (req.method === 'POST') {
      res.writeHead(201);
    } else if (req.method === 'DELETE') {
      res.writeHead(204);
    } else {
      res.writeHead(500);
    }
    res.end('');
  })
  .listen(${urlParts.port});
`;
  fs.writeFileSync(path.join(this.dir, filename), content);
});

Given('a file {string} with a server responding on {string} with {string}', function step(filename, fullURL, body) {
  const urlParts = url.parse(fullURL);
  const content = `
require('http')
  .createServer((req, res) => {
    if (req.url === '${urlParts.path}') {
      res.writeHead(200, { 'Content-Type': 'text/html' });
      res.end('');
    } else {
      res.writeHead(500);
      res.end();
    }
  })
  .listen(${urlParts.port});
`;
  fs.writeFileSync(path.join(this.dir, filename), content);
});

Given('a file named {string} with:', function step(filename, content) {
  fs.writeFileSync(path.join(this.dir, filename), content);
});

Given('I set the environment variables to:', function step(env) {
  this.env = { ...this.env, ...env.rowsHash() };
});


When(/^I run `dredd ([^`]+)`$/, function step(args) {
  this.proc = childProcess.spawnSync(`${this.dreddBin} ${args}`, [], {
    shell: true,
    cwd: this.dir,
    env: this.env,
  });
});

When('I run {string} interactively, I wait for output to contain {string}', function step(command, output, callback) {
  proc = childProcess.spawn(command, [], {
    shell: true,
    cwd: this.dir,
    env: this.env,
  });
  function read(data) {
    if (data.toString().includes(output)) {
      proc.stdout.removeListener('data', read);
      proc.stderr.removeListener('data', read);
      callback();
    }
  }

  proc.stdout.on('data', read);
  proc.stderr.on('data', read);
});


When('I wait for output to contain {string}', function step(output, callback) {
  const { proc } = this;

  function read(data) {
    throw new Error(data.toString());
    if (data.toString().includes(output)) {
      proc.stdout.removeListener('data', read);
      proc.stderr.removeListener('data', read);
      setTimeout(callback, 4500);
    }
  }

  proc.stdout.on('data', read);
  proc.stderr.on('data', read);
});

When('I connect to the server', async function step() {
  this.socket = new net.Socket();
  const connect = util.promisify(this.socket.connect.bind(this.socket));
  await connect(61321, '127.0.0.1');
});

When('It should start listening on localhost port {int}', async function step(port) {
  this.socket = new net.Socket();
  const connect = util.promisify(this.socket.connect.bind(this.socket));
  await connect(port, '127.0.0.1');
});

When('I send a JSON message to the socket:', function step(message) {
  this.socket.write(message);
  this.dataSent += message;
});

When('I send a newline character as a message delimiter to the socket', function step() {
  this.socket.write('\n');
});


Then('the exit status should be {int}', function step(status) {
  expect(this.proc.status).to.equal(status);
});

Then('the output should contain:', function step(output) {
  expect(this.proc.stdout.toString() + this.proc.stderr.toString()).to.contain(output);
});

Then('it should start listening on localhost port {int}', async function step(port) {
  this.socket = new net.Socket();
  const connect = util.promisify(this.socket.connect.bind(this.socket));
  await connect(port, '127.0.0.1'); // throws if there's an issue
  this.socket.end();
});

Then('I should receive the same response', function step(callback) {
  this.socket.on('data', (data) => {
    const dataReceived = JSON.parse(data.toString());
    const dataSent = JSON.parse(this.dataSent);
    expect(dataReceived).to.deep.equal(dataSent);
    callback();
  });
});

Then('I should be able to gracefully disconnect', function step() {
  this.socket.end();
});
