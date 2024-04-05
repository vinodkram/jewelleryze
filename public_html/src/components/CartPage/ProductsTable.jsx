import Image from "next/image";
import { useEffect, useState } from "react";
import settings from "../../../utils/settings";
import InputQuantityCom from "../Helpers/InputQuantityCom";
import CheckProductIsExistsInFlashSale from "../Shared/CheckProductIsExistsInFlashSale";
import languageModel from "../../../utils/languageModel";
import Link from "next/link";

export default function ProductsTable({
  className,
  cartItems,
  deleteItem,
  calCPriceDependQunatity,
  incrementQty,
  decrementQty,
}) {
  const [storeCarts, setItems] = useState(null);
  const [langCntnt, setLangCntnt] = useState(null);
  useEffect(() => {
    setLangCntnt(languageModel());
  }, []);
  const price = (item) => {
    if (item) {
      if (item.product.offer_price) {
        //67.89
        if (item.variants && item.variants.length > 0) {
          const prices = item.variants.map((item) =>
            item.variant_item ? parseFloat(item.variant_item.price) : 0
          );
          const sumVarient = prices.reduce((p, c) => p + c);
          return parseFloat(item.product.offer_price) + sumVarient;
        } else {
          return parseFloat(item.product.offer_price);
        }
      } else {
        if (item.variants && item.variants.length > 0) {
          const prices = item.variants.map((item) =>
            item.variant_item ? parseFloat(item.variant_item.price) : 0
          );
          const sumVarient = prices.reduce((p, c) => p + c);
          return parseFloat(item.product.price) + sumVarient;
        } else {
          return parseFloat(item.product.price);
        }
      }
    }
  };
  const totalPriceCalc = (item) => {
    if (item) {
      const prices =
        item.variants.length > 0
          ? item.variants.map((item) =>
              item.variant_item ? parseInt(item.variant_item.price) : 0
            )
          : false;
      const sumVarient = prices ? prices.reduce((p, c) => p + c) : false;
      if (sumVarient) {
        const priceWithQty = sumVarient * parseInt(item.qty);
        return parseInt(item.totalPrice) + priceWithQty;
      } else {
        return item.totalPrice * 1;
      }
    }
  };
  useEffect(() => {
    setItems(cartItems);
  });
  const { currency_icon } = settings();
  return (
    <div className={`w-full ${className || ""}`}>
      <div className="relative w-full overflow-x-auto rounded overflow-hidden border border-qpurplelow">
        <table className="w-full text-sm text-left text-qgray dark:text-gray-400">
          <tbody>
            {/* table heading */}
            <tr className="text-[13px] font-medium text-qblack bg-[#F6F6F6] whitespace-nowrap px-2 border-b border-qpurplelow uppercase">
              <td className="py-4 pl-10 block whitespace-nowrap min-w-[300px]">
                {langCntnt && langCntnt.Product}
              </td>
               <td className="py-4 hide-m whitespace-nowrap text-center min-w-[300px]">
                {langCntnt && langCntnt.Price}
              </td>
              <td className="py-4 hide-m whitespace-nowrap  text-center ">
                {langCntnt && langCntnt.quantity}
              </td>
              <td className="py-4 hide-m whitespace-nowrap  text-center min-w-[300px]">
                {langCntnt && langCntnt.total}
              </td>
              <td className="py-4 hide-m whitespace-nowrap text-right w-[114px]"></td>
            </tr>
            {/* table heading end */}
            {storeCarts &&
              storeCarts.length > 0 &&
              storeCarts.map((item) => (
                <tr
                  key={item.id}
                  className="bg-white border-b border-qpurplelow hover:bg-gray-50"
                >
					
                {/* desktop cart start */}
                  <td className="pl-10 hide-m py-4  w-[580px]">
                    <div className="flex space-x-6 items-center">
                      <div className="w-[80px] h-[80px] rounded overflow-hidden flex justify-center items-center border border-qpurplelow relative">
                        <Image
                          layout="fill"
                          src={`${
                            process.env.NEXT_PUBLIC_BASE_URL +
                            item.product.thumb_image
                          }`}
                          alt="product"
                          className="w-full h-full object-contain"
                        />
                      </div>
                      <div className="flex-1 flex flex-col">
                        <Link
                          href={{
                            pathname: "/products/[slug]",
                            query: { slug: item.product.slug },
                          }}
                          // href={{
                          //   pathname: "/single-product",
                          //   query: { slug: item.product.slug },
                          // }}
                        >
                          <a rel="noopener noreferrer">
                            <h1 className="font-medium text-[15px] text-qblack hover:text-qpurple">
                              {item.product.name}
                            </h1>
                          </a>
                        </Link>

                        <p className="text-[11px] text-qgray line-clamp-1">                                 
                            {item.variants.length !== 0 &&
                            item.variants.map((variant, i) => (
                              <span key={i}>
                                {variant.variant_item &&
                                  variant.variant_item.product_variant_name}-
                                   {variant.variant_item &&
                                  variant.variant_item.name},&nbsp;
                              </span>
                            ))}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td className="text-center py-4 px-4 hide-m">
                    <div className="flex space-x-1 items-center justify-center">
                      <span className="text-[15px] text-qblack font-medium">
                        {
                          <CheckProductIsExistsInFlashSale
                            id={item.product_id}
                            price={price(item)}
                          />
                        }
                      </span>
                    </div>
                  </td>
                  <td className="px-4 py-4 hide-m">
                    <div className="flex justify-center items-center">
                      <InputQuantityCom
                        decrementQty={decrementQty}
                        incrementQty={incrementQty}
                        calcTotalPrice={calCPriceDependQunatity}
                        productId={item.product.id}
                        cartId={item.id}
                        qyt={parseInt(item.qty)}
                      />
                    </div>
                  </td>
                  <td className="text-right py-4 w-[100px] hide-m">
                    <div className="flex space-x-1 items-center justify-center">
                      <span className="text-[15px] text-qblack font-medium">
                        <CheckProductIsExistsInFlashSale
                          id={item.product_id}
                          price={totalPriceCalc(item)}
                        />
                        {/*{totalPriceCalc(item)}*/}
                      </span>
                    </div>
                  </td>
                  <td className="text-right py-4 hide-m">
                    <div className="flex space-x-1 items-center justify-center re">
                      <span
                        onClick={() => deleteItem(item.id)}
                        className="text-qred cursor-pointer text-qgray w-2.5 h-2.5 transform scale-100 hover:scale-110 hover:text-qred transition duration-300 ease-in-out "
                      >
                        <svg
                          viewBox="0 0 10 10"
                          fill="none"
                          className="fill-current"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path d="M9.7 0.3C9.3 -0.1 8.7 -0.1 8.3 0.3L5 3.6L1.7 0.3C1.3 -0.1 0.7 -0.1 0.3 0.3C-0.1 0.7 -0.1 1.3 0.3 1.7L3.6 5L0.3 8.3C-0.1 8.7 -0.1 9.3 0.3 9.7C0.7 10.1 1.3 10.1 1.7 9.7L5 6.4L8.3 9.7C8.7 10.1 9.3 10.1 9.7 9.7C10.1 9.3 10.1 8.7 9.7 8.3L6.4 5L9.7 1.7C10.1 1.3 10.1 0.7 9.7 0.3Z" />
                        </svg>
                      </span>
                    </div>
                  </td>
                  {/* desktop cart end */}

                  {/* mobile cart start */}
                  <div className="product-view w-full hide-xl justify-between h-full ">
                    <div data-aos="fade-right" className="lg:w-1/2 xl:mr-[70px] lg:mr-[50px] aos-init aos-animate">
                      <div className="w-full">
                        <td className="pl-10  py-4  w-[380px]">
                          <div className="flex space-x-6 items-center">
                            <div className="w-[80px] h-[80px] rounded overflow-hidden flex justify-center items-center border border-qpurplelow relative">
                              <Image
                                layout="fill"
                                src={`${
                                  process.env.NEXT_PUBLIC_BASE_URL +
                                  item.product.thumb_image
                                }`}
                                alt="product"
                                className="w-full h-full object-contain"
                              />
                            </div>
                            <div className="flex-1 flex flex-col">
                              <Link
                                href={{
                                  pathname: "/products/[slug]",
                                  query: { slug: item.product.slug },
                                }}
                                // href={{
                                //   pathname: "/single-product",
                                //   query: { slug: item.product.slug },
                                // }}
                              >
                                <a rel="noopener noreferrer">
                                  <h1 className="font-medium text-[15px] text-qblack hover:text-qpurple">
                                    {item.product.name}
                                  </h1>
                                </a>
                              </Link>

                              <p className="text-[11px] text-qgray line-clamp-1">                                 
                                  {item.variants.length !== 0 &&
                                  item.variants.map((variant, i) => (
                                    <span key={i}>
                                      {variant.variant_item &&
                                        variant.variant_item.product_variant_name}-&nbsp;
                                         {variant.variant_item &&
                                        variant.variant_item.name},&nbsp;
                                    </span>
                                  ))}
                              </p>
                            </div>
                          </div>
                        </td>
                      </div>
                    </div>
                    <div className="flex-1">
                      <div className="product-details w-full mt-1 lg:mt-0">
                        <td className="text-center w-1/4 py-4 px-4">
                          <div className="flex space-x-1 items-center justify-center">
                            <span className="text-[15px] text-qblack font-medium">
                              {
                                <CheckProductIsExistsInFlashSale
                                  id={item.product_id}
                                  price={price(item)}
                                />
                              }
                            </span>
                          </div>
                        </td>
                        <td className="px-2 w-1/4 py-4">
                          <div className="flex justify-center items-center">
                            <InputQuantityCom
                              decrementQty={decrementQty}
                              incrementQty={incrementQty}
                              calcTotalPrice={calCPriceDependQunatity}
                              productId={item.product.id}
                              cartId={item.id}
                              qyt={parseInt(item.qty)}
                            />
                          </div>
                        </td>
                        <td className="text-right w-1/4 py-4 w-[100px]">
                          <div className="flex space-x-1 items-center justify-center">
                            <span className="text-[15px] text-qblack font-medium">
                              <CheckProductIsExistsInFlashSale
                                id={item.product_id}
                                price={totalPriceCalc(item)}
                              />
                              {/*{totalPriceCalc(item)}*/}
                            </span>
                          </div>
                        </td>
                        <td className="text-right w-1/4 py-4">
                          <div className="flex space-x-1 items-center justify-center re">
                            <span
                              onClick={() => deleteItem(item.id)}
                              className="text-qred cursor-pointer text-qgray w-2.5 h-2.5 transform scale-100 hover:scale-110 hover:text-qred transition duration-300 ease-in-out "
                            >
                              <svg
                                viewBox="0 0 10 10"
                                fill="none"
                                className="fill-current"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path d="M9.7 0.3C9.3 -0.1 8.7 -0.1 8.3 0.3L5 3.6L1.7 0.3C1.3 -0.1 0.7 -0.1 0.3 0.3C-0.1 0.7 -0.1 1.3 0.3 1.7L3.6 5L0.3 8.3C-0.1 8.7 -0.1 9.3 0.3 9.7C0.7 10.1 1.3 10.1 1.7 9.7L5 6.4L8.3 9.7C8.7 10.1 9.3 10.1 9.7 9.7C10.1 9.3 10.1 8.7 9.7 8.3L6.4 5L9.7 1.7C10.1 1.3 10.1 0.7 9.7 0.3Z" />
                              </svg>
                            </span>
                          </div>
                        </td>
                      </div>
                    </div>
                  </div>
                  {/* mobile cart end */}
                </tr>
              ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
