module.exports = {
  content: [
    "./pages/**/*.{js,ts,jsx,tsx}",
    "./src/components/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primarygray: "#f8f8f8",
        qblack: "#232532",
        qpurple: "#AE1C9A",
        qpurplelow: "#F9EDF7",
        qyellow: "#F2C94C",
        qred: "#EF262C",
        qgray: "#6E6D79",
        qgraytwo: "#8E8E8E",
        "qblue-white": "#CBECFF",
        "qh2-green": "#2D6F6D",
      },
      scale: {
        60: "0.6",
      },
    },
  },
  variants: {
    extend: {
      textColor: ["focus-within"],
      borderColor: ["last"],
    },
  },
  plugins: [require("@tailwindcss/line-clamp")],
};
