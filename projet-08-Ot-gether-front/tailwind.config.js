/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        jakarta: ["Plus Jakarta Sans", "sans-serif"],
      },

      fontSize: {
        xl: ["56px", { lineHeight: "70px", fontWeight: "800" }],
        l: ["40px", { lineHeight: "51px", fontWeight: "800" }],
        m: ["32px", { lineHeight: "40px", fontWeight: "800" }],
        s: ["24px", { lineHeight: "28px", fontWeight: "800" }],
        "body-m": ["18px", { lineHeight: "28px", fontWeight: "500" }],
        "body-s": ["16px", { lineHeight: "28px", fontWeight: "500" }],
      },

      colors: {
        navyBlue: "#13183f",
        grey: "#83869a",
      },

      backgroundImage: {
        gradientOrangePink:
          "linear-gradient(180deg, #EF5351 0.04%, #f02aa6 100%)",
        gradientOrangePinkHover:
          "linear-gradient(0deg, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), linear-gradient(180deg, #EF5351 0%, #F02AA6 100%)",
        gradientBluePink: "linear-gradient(180deg, #4851ff 0%, #f02aa6 99.92%)",
        gradientBluePinkHover:
          "linear-gradient(0deg, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), linear-gradient(180deg, #4851FF -54.32%, #F02AA6 100%);",
        gradientBg: "linear-gradient(180deg, #ffffff 0%, #f0f1ff 100%)",
        "hero-tablet": "url('/assets/image-hero-tablet.png')",
      },
    },
  },
  plugins: [],
};

