import { useRouter } from "next/router";
import { useEffect } from "react";
import Head from "next/head";
import React from "react";
import settings from "../../../utils/settings";
function PageHead(props) {
  const { title } = props;
  const { metaDes } = props;
  const { thumbImage } = props;
  const { favicon } = settings();
  const router = useRouter();

  return (
    <Head>
      <meta property="og:image" content={thumbImage}/>
      <meta property="og:type" content="image/png" />
      <meta property="og:url" content={ window.location.origin + router.asPath } />
      <meta property="og:title" content={title} />
      <meta property="og:description" content={ metaDes } />
      <meta property="og:image:width" content="600"/>
      <meta property="og:image:height" content="314"/>
      <title>{title}</title>
      <meta name="description" content={ metaDes } />
      
      <link
        rel="icon"
        href={`${
          favicon ? process.env.NEXT_PUBLIC_BASE_URL + favicon : "/favico.svg"
        }`}
      />
    </Head>
  );
}

export default PageHead;
