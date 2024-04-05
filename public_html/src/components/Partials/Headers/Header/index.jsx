import Image from "next/image";
import Link from "next/link";
import { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import ThinBag from "../../../Helpers/icons/ThinBag";
import Middlebar from "./Middlebar";
import Navbar from "./Navbar";
import TopBar from "./TopBar";

export default function Header({ drawerAction, settings, contact }) {
  const { cart } = useSelector((state) => state.cart);
  const [cartItems, setCartItem] = useState(null);
  //code for sticky header by abhishek start
  const [stickyClass, setStickyClass] = useState('relative');

  useEffect(() => {
    window.addEventListener('scroll', stickNavbar);

    return () => {
      window.removeEventListener('scroll', stickNavbar);
    };
  }, []);

  const stickNavbar = () => {
    if (window !== undefined) {
      let windowHeight = window.scrollY;
      windowHeight > 200 ? setStickyClass('fixed top-0 left-0 z-50 border-b border-[#ae1c9a4f]') : setStickyClass('relative');
    }
  };
  //code for sticky header by abhishek end
  useEffect(() => {
    cart && setCartItem(cart.cartProducts);
  }, [cart]);

  return (
    <header className="header-section-wrapper relative print:hidden">
      <TopBar contact={contact && contact} className="quomodo-shop-top-bar" />
      <Middlebar
        settings={settings && settings}
        className="quomodo-shop-middle-bar lg:block hidden"
      />
      <div className={`quomodo-shop-drawer ${stickyClass} lg:hidden block w-full h-[80px] bg-white`}>
        <div className="w-full h-full flex justify-between items-center px-5 py-2">
          <div className="flex" onClick={drawerAction}>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              className="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              strokeWidth="2"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M4 6h16M4 12h16M4 18h7"
              />
            </svg>
            <span className="text-sm leading-6 text-qblack font-500 cursor-pointer">&nbsp; Menu</span>
          </div>
          <div className="w-[180px] h-full relative">
            <Link href="/" passHref>
              <a>
                {settings && (
                  <Image
                    layout="fill"
                    objectFit="scale-down"
                    src={`${process.env.NEXT_PUBLIC_BASE_URL + settings.logo}`}
                    alt="logo"
                  />
                )}
              </a>
            </Link>
          </div>
          <div className="cart relative w-[70px] cursor-pointer text-qblack">
            <Link href="/cart">
              <span className="flex justify-end">
                <ThinBag className={`fill-current`} />
              </span>
            </Link>
            <span className="w-[18px] h-[18px] rounded-full text-white bg-qpurple absolute -top-2.5 -right-2.5 flex justify-center items-center text-[9px]">
              {cartItems ? cartItems.length : 0}
            </span>
          </div>
        </div>
      </div>
      <Navbar className="quomodo-shop-nav-bar lg:block hidden" />
    </header>
  );
}
