// require('http')
//   .createServer((req, res) => {
//     console.log(req)
//     if (req.url === '/message') {
//       res.writeHead(200, { 'Content-Type': 'text/html' });
//       res.end('');
//     } else {
//       res.writeHead(500);
//       res.end();
//     }
//   })
//   .listen(4567);
process.env.PATH = `${process.env.PATH}:${process.cwd()}/bin`
console.log(process.env.PATH)
