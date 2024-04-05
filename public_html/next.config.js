/** @type {import('next').NextConfig} */
const { hostname } = new URL(`${process.env.NEXT_PUBLIC_BASE_URL}`);
const nextConfig = {
  reactStrictMode: false,
  async rewrites() {
	  console.log("Rewrites called");
     return [
        {
           source: '/shop/:slug',
           destination: '/products?category=:slug'
        },
        {
           source: '/shop/:category/:sub_category',
           destination: '/products?category=:category&sub_category=:sub_category'
        },
        {
           source: '/shop/:category/:sub_category/:child_category',
           destination: '/products?category=:category&sub_category=:sub_category&child_category=:child_category'
        },
        {
           source: '/products/:product_slug',
           destination: '/single-product?slug=:product_slug'
        },
		{
           source: '/page/:slug',
           destination: '/pages?custom=:slug'
        }
     ]
  },
  swcMinify: true,
  images: {
    domains: [`${hostname}`],
  },
};

module.exports = nextConfig;
