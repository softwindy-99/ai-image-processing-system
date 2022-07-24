const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  publicPath: "./",
  transpileDependencies: true,
  devServer: {
    proxy: {
      //设置代理
      "/user": {
        target: "http://localhost:80/server/interface/user.php",
        changeOrigin: true,
      },
      "/token": {
        target: "http://localhost:80/server/interface/token.php",
        changeOrigin: true,
      }
    }
  }
})
