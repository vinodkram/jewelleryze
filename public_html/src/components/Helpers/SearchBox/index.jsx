// import Link from "next/link";
import { useState, useEffect } from "react";
import { useSelector } from "react-redux";
// import axios from "axios";
import { useRouter } from "next/router";
import languageModel from "../../../../utils/languageModel";

export default function SearchBox({ className }) {
  const router = useRouter();
  const [toggleCat, setToggleCat] = useState(false);
  const { websiteSetup } = useSelector((state) => state.websiteSetup);
  const [categories, setCategories] = useState(null);
  const [selectedCat, setSelectedCat] = useState(null);
  const [searchKey, setSearchkey] = useState("");
  const categoryHandler = (value) => {
    setSelectedCat(value);
    setToggleCat(!toggleCat);
  };
  useEffect(() => {
    if (websiteSetup) {
      setCategories(
        websiteSetup.payload && websiteSetup.payload.productCategories
      );
    }
  }, [websiteSetup]);
  const searchHandler = () => {
    if (searchKey !== "") {
      if (selectedCat) {
        router.push({
          pathname: "/search",
          query: { search: searchKey, category: selectedCat.slug },
        });
      } else {
        router.push({
          pathname: "/search",
          query: { search: searchKey },
        });
      }
    } else if (searchKey === "" && selectedCat) {
      router.push({
        pathname: "/shop/[category]",
        query: { category: selectedCat.slug },
        // pathname: "/products",
        // query: { category: selectedCat.slug },
      });
    } else {
      return false;
    }
  };
  const [langCntnt, setLangCntnt] = useState(null);
  useEffect(() => {
    setLangCntnt(languageModel());
  }, []);
  return (
    <>
      <div className="relative w-full h-full">
        <div
          className={`w-full h-full flex items-center   border border-qborder rounded-full overflow-hidden  ${
            className || ""
          }`}
        >
          <div className="flex-1 bg-red-500 h-full">
            <div className="h-full">
              <input
                value={searchKey}
                onKeyDown={(e) => e.key === "Enter" && searchHandler()}
                onChange={(e) => setSearchkey(e.target.value)}
                type="text"
                className="search-input text-base h-full placeholder:text-base"
                placeholder="Search Product..."
              />
            </div>
          </div>
          <div className="w-[1px] h-[22px] bg-qborder"></div>
          <div className="flex-1 flex items-center px-4 relative">
            <button
              onClick={() => setToggleCat(!toggleCat)}
              type="button"
              className="w-full text-base h-full  font-500 text-qgray flex justify-between items-center"
            >
              <span className="line-clamp-1">
                {selectedCat ? selectedCat.name : "All Categories"}
              </span>
              <span>
                <svg
                  width="10"
                  height="5"
                  viewBox="0 0 10 5"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <rect
                    x="9.18359"
                    y="0.90918"
                    width="5.78538"
                    height="1.28564"
                    transform="rotate(135 9.18359 0.90918)"
                    fill="#8E8E8E"
                  />
                  <rect
                    x="5.08984"
                    y="5"
                    width="5.78538"
                    height="1.28564"
                    transform="rotate(-135 5.08984 5)"
                    fill="#8E8E8E"
                  />
                </svg>
              </span>
            </button>
          </div>
          <button
            onClick={searchHandler}
            className="search-btn w-[100px]  h-full text-base font-600 "
            type="button"
          >
            {langCntnt && langCntnt.Search}
          </button>
        </div>
        {toggleCat && (
          <>
            <div
              className="w-full h-full fixed left-0 top-0 z-50"
              onClick={() => setToggleCat(!toggleCat)}
            ></div>
            <div
              className="w-[227px] h-auto absolute bg-white right-[110px] top-[58px] z-50 p-5"
              style={{ boxShadow: "0px 15px 50px 0px rgba(0, 0, 0, 0.14)" }}
            >
              <ul className="flex flex-col space-y-2">
                {categories &&
                  categories.map((item, i) => (
                    <li onClick={() => categoryHandler(item)} key={i}>
                      <span className="text-qgray text-sm font-400 border-b border-transparent hover:border-qpurple hover:text-qpurple cursor-pointer">
                        {item.name}
                      </span>
                    </li>
                  ))}
              </ul>
            </div>
          </>
        )}
      </div>
    </>
  );
}
