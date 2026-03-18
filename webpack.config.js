/**
 * `@wordpress/scripts` path-based name multi-block Webpack configuration.
 */

// Native Dependencies.
const path = require("path");

// Third-Party Dependencies.
const CopyPlugin = require("copy-webpack-plugin");
const config = require("@wordpress/scripts/config/webpack.config.js");

config.entry = {
  "download/index": path.resolve(
    process.cwd(),
    "blocks",
    "src",
    "download",
    "index.js"
  ),
};
config.output = {
  filename: "[name].js",
  path: path.resolve(process.cwd(), "blocks", "build"),
};

// Add a CopyPlugin to copy over block.json and render.php files.
config.plugins.push(
  new CopyPlugin({
    patterns: [
      {
        context: "blocks/src",
        from: `*/block.json`,
        noErrorOnMissing: true,
      },
      {
        context: "blocks/src",
        from: `*/render.php`,
        noErrorOnMissing: true,
      },
    ],
  })
);

module.exports = config;
