import Link from "next/link";
import { useEffect, useState } from "react";
import languageModel from "../../../../../utils/languageModel";
export default function TopBar({ className, contact }) {
  const [auth, setAuth] = useState(null);
  const [langCntnt, setLangCntnt] = useState(null);
  useEffect(() => {
    setAuth(JSON.parse(localStorage.getItem("auth")));
    setLangCntnt(languageModel());
  }, []);

  useEffect(() => {
    let addScript = document.createElement('script');
    addScript.setAttribute( 'src', '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit');
    document.body.appendChild(addScript);
    window.googleTranslateElementInit = googleTranslateElementInit; 
  }, []);
  
  const googleTranslateElementInit = () =>{
      new google.translate.TranslateElement({pageLanguage: 'en', 
      layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
    }, 'google_translate_element');
  }
  return (
    <>
      <div
        className={`w-full bg-qpurplelow h-6 border-b border-[#ae1c9a4f] ${
          className || ""
        }`}
      >
        <div className="container-x mx-auto h-full">
          <div className="flex justify-between items-center h-full">
            <div className="topbar-nav">
              <ul className="flex space-x-6">
                <li>
                  {auth ? (
                    <Link href="/profile#dashboard" passHref>
                      <a rel="noopener noreferrer">
                        <span className="text-sm leading-6 text-qblack font-500 cursor-pointer">
                          {langCntnt && langCntnt.Account}
                        </span>
                      </a>
                    </Link>
                  ) : (
                    <Link href="/login" passHref>
                      <a rel="noopener noreferrer">
                        <span className="text-sm leading-6 text-qblack font-500 cursor-pointer">
                          {langCntnt && langCntnt.Account}
                        </span>
                      </a>
                    </Link>
                  )}
                </li>
                <li className="sm:block hidden">
                  <Link href="/tracking-order" passHref>
                    <a rel="noopener noreferrer">
                      <span className="text-sm leading-6 text-qblack font-500 cursor-pointer">
                        {langCntnt && langCntnt.Track_Order}
                      </span>
                    </a>
                  </Link>
                </li>
                <li>
                  <Link href="/faq" passHref>
                    <a rel="noopener noreferrer">
                      <span className="text-sm leading-6 text-qblack font-500 cursor-pointer">
                        {langCntnt && langCntnt.Support}
                      </span>
                    </a>
                  </Link>
                </li>
              </ul>
            </div>
            <div className="topbar-dropdowns sm:block hidden">
              <a href={`tel:${contact && contact.phone}`}>
                <div className="flex space-x-2 items-center">
                  <span className="text-qblack text-sm font-medium">
                    {langCntnt && langCntnt.Need_help}
                  </span>
                  <span className="text-xs text-qpurple font-bold leading-none">
                    {contact && contact.phone}
                  </span>
                </div>
              </a>
            </div>
            <div id="google_translate_element" className="flex justify-end"></div>
          </div>
        </div>
      </div>
    </>
  );
}

