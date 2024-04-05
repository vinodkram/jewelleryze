const { createServer } = require("http");
const { parse } = require("url");
const next = require("next");
// const absoluteUrl = require('next-absolute-url').default;

const dev = process.env.NODE_ENV !== "production";
const hostname = "localhost";
const port = process.env.port || 50001;
// when using middleware `hostname` and `port` must be provided below
const app = next({ dev, hostname, port });
const handle = app.getRequestHandler();

app.prepare().then(() => {
  createServer(async (req, res) => {
    try {
      // Code to force redirect to https start
      const { url } = req;
      // const { protocol, host } = absoluteUrl(req);
      //const protocol = req.headers["x-forwarded-proto"] || req.connection.encrypted?"https":"http";
      //console.log(protocol);
      // console.log(host);
      // if (process.env.NODE_ENV != 'development' && !req.secure) {
      //if (process.env.NODE_ENV != 'development' && protocol != 'https') 
      //{
        // res.writeHead(301,{Location: `https://jewelleryze.com/${req.url}`});
         //res.end();
         //return {};
      //}
      // Code to force redirect to https end
      // Be sure to pass `true` as the second argument to `url.parse`.
      // This tells it to parse the query portion of the URL.
      const parsedUrl = parse(req.url, true);
      const { pathname, query } = parsedUrl;

      if (pathname === "/a") {
        await app.render(req, res, "/a", query);
      } else if (pathname === "/b") {
        await app.render(req, res, "/b", query);
      } else {
        await handle(req, res, parsedUrl);
      }
    } catch (err) {
      console.error("Error occurred handling", req.url, err);
      res.statusCode = 500;
      res.end("internal server error");
    }
  }).listen(port, (err) => {
    if (err) throw err;
    console.log(`> Ready on http://${hostname}:${port}`);
  });
});