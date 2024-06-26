import { useRouter } from "next/router";
import { useEffect, useState } from "react";
import { toast } from "react-toastify";
import apiRequest from "../../../../utils/apiRequest";
import InputCom from "../../Helpers/InputCom";
import LoaderStyleOne from "../../Helpers/Loaders/LoaderStyleOne";
import Layout from "../../Partials/Layout";
import Image from "next/image";
import { useSelector } from "react-redux";
import languageModel from "../../../../utils/languageModel";

export default function ForgotPass() {
  const router = useRouter();
  const { websiteSetup } = useSelector((state) => state.websiteSetup);
  const [email, setEmail] = useState("");
  const [loading, setLoading] = useState(false);
  const [resetPass, setResetpass] = useState(false);
  const [otp, setOtp] = useState("");
  const [forgotUser, setForgotUser] = useState(true);
  const [newPass, setNewPass] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [errors, setErrors] = useState(null);
  const [imgThumb, setImgThumb] = useState(null);
  useEffect(() => {
    if (websiteSetup) {
      setImgThumb(websiteSetup.payload.login_page_image);
    }
  }, [websiteSetup]);

  const doForgot = async () => {
    setLoading(true);
    await apiRequest
      .forgotPass({
        email: email,
      })
      .then((res) => {
        setResetpass(true);
        setForgotUser(false);
        setLoading(false);
        setErrors(null);
      })
      .catch((err) => {
        console.log("twilio err: ", err);
        setLoading(false);
        // added by abhishek for when not sent twilio otp
        setResetpass(true);
        setForgotUser(false);
         // added by abhishek for when not sent twilio otp
        setErrors(err.response);
        if (err.response) {
          if (err.response.data.notification) {
            toast.error(err.response.data.notification);
          }else if (err.response.data.message) {
            toast.error(err.response.data.message);
          } else {
            return false;
          }
        } else {
          return false;
        }
      });
  };
  const doReset = async () => {
    setLoading(true);
    await apiRequest
      .resetPass(
        {
          email: email,
          password: newPass,
          password_confirmation: confirmPassword,
        },
        otp
      )
      .then((res) => {
        setLoading(false);
        router.push("login");
        toast.success(res.data.notification);
      })
      .catch((err) => {
        console.log(err);
        setLoading(false);
        setErrors(err.response);
        if (err.response) {
          if (err.response.data.notification) {
            toast.error(err.response.data.notification);
          } else {
            return false;
          }
        } else {
          return false;
        }
      });
  };
  const [langCntnt, setLangCntnt] = useState(null);
  useEffect(() => {
    setLangCntnt(languageModel());
  }, []);
  return (
    <Layout childrenClasses="pt-0 pb-0 min-h-0">
      <div className="login-page-wrapper w-full relative">
        <div className="w-full h-full h-screen absolute left-0 top-0">
          <div className="w-full h-full relative z-10">
            {imgThumb && (
              <Image
                layout="fill"
                src={`${process.env.NEXT_PUBLIC_BASE_URL + imgThumb.image}`}
                alt="login"
              />
            )}
            <div className="bg-[#232532] bg-opacity-50 relative w-full h-full absolute left-0 top-0"></div>
          </div>
        </div>
        <div className="container-x mx-auto">
          <div className="lg:flex items-center justify-center relative py-[60px]">
            <div className="lg:w-[572px] w-full h-[450px] bg-white flex flex-col justify-center sm:p-10 p-5 border border-[#E0E0E0] rounded-lg relative z-20">
              {forgotUser ? (
                <div className="w-full">
                  <div className="title-area flex flex-col justify-center items-center relative text-center mb-7">
                    <h1 className="text-[34px] font-bold leading-[74px] text-qblack">
                      {langCntnt && langCntnt.Forgot_password}
                    </h1>
                  </div>
                  <div className="input-area">
                    <div className="input-item mb-5">
                      <InputCom
                        placeholder={langCntnt && langCntnt.email}
                        label={langCntnt && langCntnt.Email_Address + "*"}
                        name="email"
                        type="email"
                        inputClasses="h-[50px]"
                        inputHandler={(e) => setEmail(e.target.value.trim())}
                        value={email}
                      />
                    </div>

                    <div className="signin-area mb-3.5">
                      <div className="flex justify-center">
                        <button
                          onClick={doForgot}
                          type="button"
                          disabled={email ? false : true}
                          className="bg-qpurple rounded disabled:bg-opacity-60 disabled:cursor-not-allowed  mb-6 text-sm text-white w-full h-[50px] font-semibold flex justify-center bg-purple items-center"
                        >
                          <span>{langCntnt && langCntnt.Send}</span>
                          {loading && (
                            <span
                              className="w-5 "
                              style={{ transform: "scale(0.3)" }}
                            >
                              <LoaderStyleOne />
                            </span>
                          )}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              ) : resetPass ? (
                <div className="w-full">
                  <div className="title-area flex flex-col justify-center items-center relative text-center mb-7">
                    <h1 className="text-[34px] font-bold leading-[74px] text-qblack">
                      {langCntnt && langCntnt.Reset_Password}
                    </h1>
                    <div className="shape -mt-6">
                      <svg
                        width="354"
                        height="30"
                        viewBox="0 0 354 30"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M1 28.8027C17.6508 20.3626 63.9476 8.17089 113.509 17.8802C166.729 28.3062 341.329 42.704 353 1"
                          stroke="#27AE60"
                          strokeWidth="2"
                          strokeLinecap="round"
                        />
                      </svg>
                    </div>
                  </div>
                  <div className="input-area">
                    <div className="input-item mb-5">
                      <InputCom
                        placeholder="● ● ● ● ● ●"
                        label={langCntnt && langCntnt.OTP + "*"}
                        name="otp"
                        type="text"
                        inputClasses="h-[50px]"
                        value={otp}
                        error={errors}
                        inputHandler={(e) => setOtp(e.target.value.trim())}
                      />
                    </div>
                    <div className="input-item mb-5">
                      <InputCom
                        placeholder="● ● ● ● ● ●"
                        label={langCntnt && langCntnt.New_Password + "*"}
                        name="new_password"
                        type="password"
                        inputClasses="h-[50px]"
                        value={newPass}
                        error={
                          errors &&
                          errors.data.errors &&
                          Object.hasOwn(errors.data.errors, "password")
                            ? true
                            : false
                        }
                        inputHandler={(e) => setNewPass(e.target.value.trim())}
                      />
                      {errors &&
                      errors.data.errors &&
                      Object.hasOwn(errors.data.errors, "password") ? (
                        <span className="text-sm mt-1 text-qred">
                          {errors.data.errors.password[0]}
                        </span>
                      ) : (
                        ""
                      )}
                    </div>
                    <div className="input-item mb-5">
                      <InputCom
                        placeholder="● ● ● ● ● ●"
                        label={langCntnt && langCntnt.Confirm_Password + "*"}
                        name="Confirm Password"
                        type="password"
                        inputClasses="h-[50px]"
                        value={confirmPassword}
                        error={
                          errors &&
                          errors.data.errors &&
                          Object.hasOwn(errors.data.errors, "password")
                            ? true
                            : false
                        }
                        inputHandler={(e) =>
                          setConfirmPassword(e.target.value.trim())
                        }
                      />
                      {errors &&
                      errors.data.errors &&
                      Object.hasOwn(errors.data.errors, "password") ? (
                        <span className="text-sm mt-1 text-qred">
                          {errors.data.errors.password[0]}
                        </span>
                      ) : (
                        ""
                      )}
                    </div>

                    <div className="signin-area mb-3.5">
                      <div className="flex justify-center">
                        <button
                          onClick={doReset}
                          type="button"
                          disabled={
                            otp && confirmPassword && newPass ? false : true
                          }
                          className="bg-qpurple rounded disabled:bg-opacity-60 disabled:cursor-not-allowed  mb-6 text-sm text-white w-full h-[50px] font-semibold flex justify-center bg-purple items-center"
                        >
                          <span>{langCntnt && langCntnt.Reset}</span>
                          {loading && (
                            <span
                              className="w-5 "
                              style={{ transform: "scale(0.3)" }}
                            >
                              <LoaderStyleOne />
                            </span>
                          )}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              ) : (
                ""
              )}
            </div>
          </div>
        </div>
      </div>
    </Layout>
  );
}
