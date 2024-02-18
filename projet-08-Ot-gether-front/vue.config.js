module.exports = {
  devServer: {
    proxy: "http://localhost:8080/",
  },
  plugins: [
    {
      src: "pusher-vue",
      options: {
        appId: process.env.PUSHER_APP_ID,
        appKey: process.env.PUSHER_APP_KEY,
        appSecret: process.env.PUSHER_APP_SECRET,
        appCluster: process.env.PUSHER_APP_CLUSTER,
      }
    }
  ]
};
