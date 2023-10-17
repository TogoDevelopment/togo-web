import React, { useEffect, useState } from "react";
import "./Header.css";
import {
    Menu,
    Button,
    MenuButton,
    MenuList,
    MenuItem,
    Box,
    Icon,
    Text,
    Flex,
    Image
} from "@chakra-ui/react";
import { useSelector, useDispatch } from "react-redux";
import { useHistory } from "react-router";
import { IoIosWallet, IoIosCard, IoIosExit, IoMdGitNetwork } from 'react-icons/io';
import { MdOutlinePriceCheck } from 'react-icons/md';
import { IoCaretBack } from 'react-icons/io5';
import { AiFillHome } from 'react-icons/ai';
import { FaBoxes } from 'react-icons/fa';
import { Link } from "react-router-dom";
import {
    getWallet,
    updateWebNotificationToken,
    getNotifications,
    unmarkNotification
} from "../../APIs/ProfileAPIs";
import { getTotalOrdersNum } from "../../APIs/OrdersAPIs";
import { setWallet, toastMessage } from "../../Actions/GeneralActions";
import { LOGOUT } from "../../Actions/ActionsTypes";
import phoenixLogo from '../../assets/phoenix_logo_white.png';
import translate from "../../i18n/translate";
import LanguageSelector from "../LanguageSelector/LanguageSelector";
import Loader from "../Loader/Loader";
import { isTransporter } from "../../Util";
import { HiOutlineDotsHorizontal } from 'react-icons/hi';
import { BsFillBellFill } from 'react-icons/bs';
import { FaUsersGear } from 'react-icons/fa6';
import { toastNotification } from "../../Actions/GeneralActions";

const styles = {
    settingsButton: {
        variant: "ghost",
        size: "lg",
        iconSpacing: 0,
        lineHeight: "0.2",
        _active: {
            background: "none",
            transform: "scale(1.3)",
            color: "black",
            opacity: "0.5"
        },
        _hover: { transform: "scale(1.3)" },
        _focus: { outline: "none" }
    },
    notificationsButton: {
        position: "relative",
        variant: "ghost",
        size: "lg",
        iconSpacing: 0,
        lineHeight: "0.2",
        _active: {
            background: "none",
            transform: "scale(1.3)",
            color: "black",
            opacity: "0.5"
        },
        _hover: { transform: "scale(1.3)" },
        _focus: { outline: "none" }
    },
    actionsContainer: {
        position: "absolute",
        top: "1rem",
        right: "1rem",
        alignItems: "center",
        background: "#00000036",
        borderRadius: "29px"
    },
    headerItemsContainer: {
        alignItems: "center",
        cursor: "pointer",
        px: "1.5rem",
        _hover: {
            transform: "scale(1.1)",
            underline: "unset",
        }
    },
    horizontalBreakLine: {
        w: "3px",
        h: "20px",
        bgColor: "white",
        borderRadius: 999,
        opacity: 0.5
    },
    link: {
        color: "white",
        textDecoration: "none"
    },
    backBtnContainer: {
        position: "absolute",
        left: "1rem",
        top: "1rem",
        alignItems: "center",
        pt: "0.2rem"
    },
    logo: {
        position: "absolute",
        top: "40%",
        left: "50%",
        transform: "translate(-50%, -50%)",
        /* _hover: {
            opacity: 0.5,
            transform: "scale(1.2) translate(-40%, -40%)"
        } */
    },
    headerStyle: {
        position: "relative",
        background: "linear-gradient(to right, #129CD5, #0f82b3)",
        color: "#fff",
        textAlign: "center",
        padding: "40px 0",
        height: "140px",
        direction: "ltr"
    },
    addNewOrderContainer: {
        position: "absolute",
        bottom: "1rem",
        right: "1rem",
        alignItems: "center",
        zIndex: "1"
    },
    customerType: {
        position: "absolute",
        bottom: "1rem",
        right: "0",
        left: "0",
        fontSize: "1.5rem",
        zIndex: "0"
    },
    nameDisplay: {
        position: "absolute",
        bottom: "1rem",
        right: "1rem",
        fontSize: "1rem",
    }
};

function Header() {

    /* greet the user */

    let myDate = new Date();
    let hrs = myDate.getHours();

    let greet;

    if (hrs < 12)
        greet = 'Good Morning';
    else if (hrs >= 12 && hrs <= 17)
        greet = 'Good Afternoon';
    else if (hrs >= 17 && hrs <= 24)
        greet = 'Good Evening';

    const authenticated = useSelector(state => state.general.authenticated);
    const wallet = useSelector(state => state.general.wallet);
    const history = useHistory();
    const [isHomePage, setIsHomePage] = useState(history.location.pathname === '/orders' || history.location.pathname === '/');
    let dispatch = useDispatch();

    const [totalOrdersNum, setTotalOrdersNum] = useState(0);

    const [notifications, setNotifications] = useState([])
    const [refreshNotification, setRefreshNotification] = useState(false)
    const [showActiveNotifications, setShowActiveNotifications] = useState(false)

    const handleLogout = () => {
        dispatch({
            type: LOGOUT
        });

        updateWebNotificationToken(null).then((res) => {
            console.log(res.data);
        })

        localStorage.removeItem("userId");
        localStorage.removeItem("fullName");
        localStorage.removeItem("TokenDevice");
        localStorage.removeItem("UserType");

        history.push("/");
    };

    const unmarkNotificationHandler = (notificationId) => {
        unmarkNotification(notificationId).then((res) => {
            if (res.data.status == "error") {
                console.log(res.data.error)
            } else {
                setRefreshNotification(!refreshNotification)
            }
        })
    }

    useEffect(() => {
        getTotalOrdersNum().then((res) => {
            setTotalOrdersNum(res.data.ordersNum);
        })
    }, [authenticated, dispatch])

    useEffect(() => {
        if (authenticated) {
            getWallet().then(({ data: { server_response } }) => {
                dispatch(setWallet(server_response[0].TransporterBalance));
            }).catch(err => {
                dispatch(toastMessage(err));
            })
        }
        return () => setWallet(null);
    }, [authenticated, dispatch]);

    useEffect(() => {
        let historyUnListen = history.listen(location => {
            setIsHomePage(location.pathname === '/account/orders' || location.pathname === '/');
        });
        return () => historyUnListen();
    }, [history]);

    useEffect(() => {
        getNotifications().then((res) => {
            if (res.data.status == "error") {
                console.log(res.data.error);
                dispatch(toastNotification("Error!", "Something went wrong", "error"));
            } else {
                const tempNotifications = res.data.notifications;
                setNotifications(tempNotifications);

                setShowActiveNotifications(false);

                for (let i = 0; i < tempNotifications.length; i++) {
                    const element = tempNotifications[i];
                    if (element.isRead == 0) {
                        setShowActiveNotifications(true);
                        break;
                    }
                }
            }
        })
    }, [refreshNotification])

    return (
        <>
            <header style={styles.headerStyle} className="togo-header" >
                {authenticated && (
                    <Box>
                        {/* edited (display name) */}
                        <div className="d-flex justify-content-between" style={styles.nameDisplay}>
                            {greet === "Good Morning" ? <svg style={{ marginTop: "2px", marginRight: "5px", color: "yellow" }} xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" className="bi bi-brightness-high" viewBox="0 0 16 16">
                                <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
                            </svg> : greet === "Good Afternoon" ? <svg style={{ marginTop: "2px", marginRight: "5px", color: "orange" }} xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" className="bi bi-brightness-alt-high" viewBox="0 0 16 16">
                                <path d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3zm8 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zm-13.5.5a.5.5 0 0 0 0-1h-2a.5.5 0 0 0 0 1h2zm11.157-6.157a.5.5 0 0 1 0 .707l-1.414 1.414a.5.5 0 1 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm-9.9 2.121a.5.5 0 0 0 .707-.707L3.05 5.343a.5.5 0 1 0-.707.707l1.414 1.414zM8 7a4 4 0 0 0-4 4 .5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5 4 4 0 0 0-4-4zm0 1a3 3 0 0 1 2.959 2.5H5.04A3 3 0 0 1 8 8z" />
                            </svg> : <svg style={{ marginTop: "2px", marginRight: "5px", color: "blue" }} xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" className="bi bi-moon-stars" viewBox="0 0 16 16">
                                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z" />
                                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
                            </svg>}
                            {greet}
                            <span style={{ fontWeight: "lighter", marginRight: "10px", marginLeft: "10px" }}>
                                {/* {localStorage.getItem("fullName")} */}
                            </span>
                        </div>
                        {!isHomePage && <Flex {...styles.backBtnContainer}>
                            <Flex {...styles.headerItemsContainer} onClick={() => { history.goBack() }}>
                                <Icon as={IoCaretBack} mr={2} fontSize="2xl" />
                                <Text>{translate("HEADER.BACK")}</Text>
                            </Flex>
                            <Flex {...styles.headerItemsContainer} onClick={() => { history.push("/account/main/current-orders") }}>
                                <Icon as={AiFillHome} mr={2} fontSize="2xl" />
                                <Text>{translate("HEADER.HOME")}</Text>
                            </Flex>
                        </Flex>}
                        <Flex {...styles.actionsContainer}>

                            <Flex {...styles.headerItemsContainer}>
                                <Icon as={FaBoxes} mr={2} fontSize="2xl" />
                                <Text>{translate("HEADER.TOTAL_ORDERS")}: {totalOrdersNum}</Text>
                            </Flex>

                            {/* ------------------------------ */} <Box {...styles.horizontalBreakLine} /> {/* ------------------------------ */}

                            {localStorage.getItem("userId") != 97 && <Link to='/account/main/financial-transaction' style={styles.link}>
                                <Flex {...styles.headerItemsContainer}>
                                    <Icon as={IoIosWallet} fontSize="2xl" />
                                    {isNaN(wallet) ? <Loader color="white" width="40px" height="40px" /> : <Text fontSize="14px">{wallet} NIS</Text>}
                                </Flex>
                            </Link>}

                            {/* ------------------------------ */} <Box {...styles.horizontalBreakLine} /> {/* ------------------------------ */}

                            <LanguageSelector />

                            {/* ------------------------------ */} <Box {...styles.horizontalBreakLine} /> {/* ------------------------------ */}

                            <Menu>
                                <MenuButton {...styles.settingsButton} as={Button} rightIcon={<HiOutlineDotsHorizontal />} />
                                <MenuList style={{ position: "relative", zIndex: "2" }} color="black">
                                    {/* <MenuItem icon={<IoMdPerson />}
                                        onClick={() => { history.push("/account/account-details") }}>{translate("HEADER.ACCOUNT_PROFILE")}</MenuItem> */}
                                    {/*   {isTransporter() && <MenuItem icon={<MdOutlinePriceCheck />}
                                        onClick={() => { history.push("/account/cities-prices") }}>{translate("HEADER.CITIES_PRICES")}</MenuItem>} */}

                                    {/* {!isTransporter() && <MenuItem
                                        icon={<IoMdGitNetwork />}
                                        onClick={() => { history.push("/account/my-network") }}
                                    >
                                        {translate("NETWORK.NETWORK_TITLE")}
                                    </MenuItem>} */}

                                    {isTransporter() && <MenuItem
                                        icon={<FaUsersGear />}
                                        onClick={() => history.push("/account/manage-clients")}
                                    >
                                        {translate("HEADER.MANAGE_CLIENTS")}
                                    </MenuItem>}

                                    <MenuItem
                                        icon={<IoIosCard />}
                                        onClick={() => history.push("/account/financial-management")}
                                    >
                                        {translate("HEADER.REQUEST_WITHDRAWAL")}
                                    </MenuItem>

                                    <MenuItem
                                        icon={<IoIosExit />}
                                        onClick={handleLogout}
                                    >
                                        {translate("HEADER.LOGOUT")}
                                    </MenuItem>

                                </MenuList>
                            </Menu>

                            {/* ------------------------------ */} {!isTransporter() && <Box {...styles.horizontalBreakLine} />} {/* ------------------------------ */}

                            {!isTransporter() && <Menu>
                                <MenuButton {...styles.notificationsButton} as={Button} rightIcon={<BsFillBellFill />} />
                                {showActiveNotifications && <div className="notification-bell-active"></div>}
                                <MenuList style={{ position: "relative", zIndex: "2", maxHeight: "500px", overflowY: "scroll" }} color="black">
                                    {
                                        notifications?.length == 0 ? <MenuItem>there is no notifications yet</MenuItem> :
                                            notifications?.map((notification, index) => {
                                                return <MenuItem
                                                    key={index}
                                                    onClick={() => { unmarkNotificationHandler(notification.id); history.push(`/account/Order/${notification.orderId}`) }}
                                                    style={{ position: "relative", display: "flex", flexDirection: "column", borderLeft: notification.isRead == 0 && "5px solid red", borderBottom: "1px solid lightgray", marginBottom: "5px", backgroundColor: notification.isRead == 0 && "#fceaea" }}
                                                >
                                                    <p className="h6" style={{ fontWeight: "bold", width: "100%" }}>{(localStorage.getItem("Language") || "en") === "en" ? notification.titleEn : notification.titleAr}</p>
                                                    {!!notification.descriptionEn && !!notification.descriptionAr &&
                                                        <p style={{ color: "gray", width: "100%" }}>{(localStorage.getItem("Language") || "en") === "en" ? notification.descriptionEn : notification.descriptionAr}</p>
                                                    }
                                                    {notification.isRead == 0 && <div className="notification-active"></div>}
                                                </MenuItem>
                                            })
                                    }
                                </MenuList>
                            </Menu>}

                        </Flex>
                        {<span style={styles.customerType}>{isTransporter() === true ? 'Transporter' : 'Client'}</span>}
                    </Box>
                )
                }
                <Link to="/">
                    <Image
                        {...styles.logo}
                        src={phoenixLogo}
                        w={100} />
                </Link>
            </header >
        </>
    );
}

export default Header;
