var cloudscraper = require('cloudscraper');


var options = {
  method: 'GET',
  url:'https://beta.exitus.me',
};
 
cloudscraper(options).then(console.log);
