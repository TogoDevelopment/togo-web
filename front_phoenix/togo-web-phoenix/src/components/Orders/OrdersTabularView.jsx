import React, { useCallback, useEffect, useState } from "react";
import './OrdersTabularView.css';
import translate from "../../i18n/translate";
import { Link } from "react-router-dom";
import { CancelOrder } from "../OrdersManager/CancelOrder";
import DynamicTable from "../DynamicTable/DynamicTable";
import { isTransporter, isTransporterMaster } from "../../Util";
import { Badge, ToggleButton, Button } from "react-bootstrap";
import { updateOrderReviewedStatus, updateReviewedOrders } from "../../APIs/OrdersAPIs";

export const PackageTypes = {
    "1": "FOOD",
    "2": "SMALL_PACKAGE_AND_ENVELOPS",
    "3": "MEDIUM_PACKAGE",
    "4": "LARGE_PACKAGE"
};

export const DeliveryTypes = {
    "1": "DELIVERY",
    "2": "COD",
    "3": "PICKUP",
    "4": "PAP"
};

export const deliverFormatter = (data) => {
    if (DeliveryTypes[parseInt(data.DeliveryWays)]) {
        return translate("ORDERS." + DeliveryTypes[parseInt(data.DeliveryWays)])
    }
    return "";
}

export const packageFormatter = (data) => {
    if (PackageTypes[parseInt(data.PackageType)]) {
        return translate("ORDERS." + PackageTypes[parseInt(data.PackageType)])
    }
    return "";
}

/* edited (deliverFormatter added) */
export const deliverFormatterTransporter = (data) => {
    if (DeliveryTypes[parseInt(data.deliveryWay)]) { // edited (old => data.DeliveryWays)
        return translate("ORDERS." + DeliveryTypes[parseInt(data.deliveryWay)])
    }
    return "";
}

/* edited (packageFormatter added) */
export const packageFormatterTransporter = (data) => {
    if (PackageTypes[parseInt(data.TypeLoad)]) { // edited (old => data.PackageType)
        return translate("ORDERS." + PackageTypes[parseInt(data.TypeLoad)])
    }
    return "";
}

/* edited (timeFormatter added) */
export const timeFormatter = (data) => {
    if (data.DateLoad) {
        return data.DateLoad.split(" ")[1];
    }
    return "";
}

/* edited (dateFormatter added) */
export const dateFormatter = (data) => {
    if (data.orderTime) {
        return data.orderTime.split(" ")[0];
    }
    return "";
}

/* edited (bidsCountFormat added) */
export const bidsCountFormat = (data) => {
    if (data.bidsCount) {
        if (data.bidsCount !== "0") {
            return data.bidsCount;
        }

        return "--"
    }
    return "";
}

/* edited (sourceAddressFormatter added) */
export const sourceAddressFormatter = (data) => {

    return data.senderVillageName + ", " + data.senderRegionName;
}

/* edited (desAddressFormatter added) */
export const desAddressFormatter = (data) => {

    return data.receiverVillageName + ", " + data.receiverRegionName;
}

/* edited (transporterStatusFormattter added) */
export const statusFormat = (data) => {
    // console.log(data)
    if (data.IsStuckOrder == 1 && data.IsReturnedOrder == 0) {
        return <Badge bg="danger">Order Stuck</Badge>
    }

    if (data.IsReturnedOrder == 1) {
        return <Badge bg="danger">Order Returned</Badge>
    }

    if (data.orderStatus == "Deleted") {
        return <Badge bg="danger">{data.orderStatus}</Badge>
    }

    return <Badge bg="primary">{data.orderStatus}</Badge>
}

export const clientOrderStatusFotmatter = (data) => {

    if (data.bidsCount) {
        if (data.bidsCount == 0) {
            return <Badge bg="primary">Waiting for bid</Badge>
        } else if (data.bidsCount == 1) {
            return <Badge bg="primary">{"1 Bid"}</Badge>
        }
        return <Badge bg="primary">{data.bidsCount + " Bids"}</Badge>
    }

    return statusFormat(data)
}

export const OrdersTabularView = ({ socket, orders, currentPage, update, assignOrders }) => {

    const [bidPrice, setBidPrice] = useState("-");

    const [columns, setColumns] = useState([]);
    const [assignedOrders, setAssignedOrders] = useState([]);

    const [reviewList, setRreviewList] = useState([]);
    const [totalCOD, setTotalCOD] = useState(0);

    const showDetailsButton = useCallback((orderId) =>
        <Link
            to={{
                pathname: `/account/Order/${orderId}`,
                state: { currentPage: currentPage },
            }}
            style={{
                paddingRight: "20%",
                paddingLeft: "20%",
                border: "none",
                width: "100%",
                textAlign: "center",
            }}
            className="btn btn-primary btn-rounded btn-grad"
        >
            {translate("ORDERS.SHOW")}
        </Link>, [currentPage]);

    const addToAssign = (isChecked, orderId) => {

        const tempId = parseInt(orderId);

        if (isChecked) {
            assignedOrders.push(tempId);
        } else {
            const idIndex = assignedOrders.findIndex(id => id === tempId);
            assignedOrders.splice(idIndex, 1);

            assignOrders(assignedOrders);
        }
    }

    const checkOrderReviewedHandler = (status, orderId) => {
        // console.log(status + " - " + orderId)

        updateOrderReviewedStatus(status, orderId).then(res => {
            // document.getElementById("tr-" + orderId).checked = true;;
            // console.log(res.data);
            // update();
        })
    }

    const addRemToReview = (status, orderId, COD) => {
        // console.log("status: " + status + " - orderId: " + orderId + " - COD: " + COD);
        if (!status) {
            setRreviewList(reviewList => reviewList.filter(id => id !== orderId));
            setTotalCOD(prevState => prevState - parseInt(COD));
        } else {
            setRreviewList(reviewList => [...reviewList, orderId]);
            setTotalCOD(prevState => prevState + parseInt(COD));
        }
    }

    const saveReviewed = () => {
        // console.log(reviewList);

        const isToReview = currentPage === "previous-orders";

        updateReviewedOrders(reviewList, isToReview).then(res => {
            // console.log(res.data);

            update();
        });
    }

    useEffect(() => {

        let newColumns = [];

        /* differ between transporter and client columns */
        if (isTransporter()) { // transporter columns

            newColumns = [
                {
                    label: translate("ORDERS.ORDER_NUM"),
                    key: "orderId"
                },
                /* {
                    label: translate("ORDERS.DELIVERY_TYPE"),
                    key: "deliveryWay",
                    format: deliverFormatterTransporter
                }, */
                /* {
                    label: translate("ORDERS.PACKAGE_TYPE"),
                    key: "TypeLoad",
                    format: packageFormatterTransporter
                }, */
                {
                    label: "COD",
                    key: "cod",
                    format: ({ cod }) => {
                        return <>{!!cod ? cod : 0}</>
                    }
                },
                {
                    label: translate("ORDERS.ORDER_DATE"),
                    key: "orderTime",
                    format: dateFormatter
                },
                /* {
                    label: translate("ORDERS.ORDER_TIME"),
                    key: "DateLoad",
                    format: timeFormatter
                }, */
                {
                    label: translate("ORDERS.FROM_CITY"),
                    key: "senderVillageName",
                    format: sourceAddressFormatter
                },
                {
                    label: translate("ORDERS.RECEIVER_NAME"),
                    key: "receiverName"
                },
                {
                    label: translate("ORDERS.TO_CITY"),
                    key: "receiverVillageName",
                    format: desAddressFormatter
                },
                {
                    label: translate("ORDERS.ORDER_STATUS"),
                    key: "order_status",
                    format: statusFormat
                },
                {
                    label: translate("ORDERS.FULL_DETAILS"),
                    key: "orderId",
                    format: ({ orderId }) => showDetailsButton(orderId)
                }
            ];

            if (currentPage !== "all-orders") {

                newColumns.splice(1, 0, {
                    label: translate("ORDERS.FOREIGN_BARCODE"),
                    key: "foreignOrderBarcode",
                    format: ({ foreignOrderId, foreignOrderBarcode }) => {
                        return <>{!!foreignOrderBarcode ? foreignOrderBarcode : !!foreignOrderId ? foreignOrderId : "-"}</>
                    }
                });
            }

            if (currentPage === "previous-orders"  || currentPage === "reviewed-orders") {
                newColumns.splice(0, 0, {
                    label: translate("ORDERS.REVIEWED"),
                    key: "assign",
                    format: ({ orderId, isReviewed, cod }, reviewed, handleCheck) => {
                        let loadCost = !!cod ? cod : 0;
                        return <input className="form-check-input" defaultChecked={currentPage === "previous-orders" ? (reviewed == 1 ? true : false) : (reviewed == 1 ? false : true)} style={{ cursor: "pointer" }} type="checkbox" value={orderId} id="flexCheckDefault" onClick={(event) => { /* checkOrderReviewedHandler(event.target.checked, event.target.value); */ addRemToReview(event.target.checked, event.target.value, loadCost); handleCheck() }} />
                    }
                });
            }

            /* check to assign */
            if (currentPage === "current-orders" && false) {
                newColumns.splice(0, 0, {
                    label: translate("ORDERS.ASSIGN"),
                    key: "assign",
                    format: ({ id, AssignerId, isAssigned }) => {
                        return <>{(AssignerId === localStorage.getItem("userId") && isAssigned == 0) || (AssignerId !== localStorage.getItem("userId") && isAssigned == 2) ?
                            <input className="form-check-input" style={{ cursor: "pointer" }} type="checkbox" value={id} id="flexCheckDefault" onClick={(event) => { addToAssign(event.target.checked, event.target.value) }} /> :
                            <input className="form-check-input" disabled />
                        }</>
                    }
                });
            }
        } else { // client columns
            newColumns = [
                {
                    label: translate("ORDERS.ORDER_NUM"),
                    key: "orderId"
                },
                /* {
                    label: translate("ORDERS.DELIVERY_TYPE"),
                    key: "DeliveryWays",
                    format: deliverFormatter
                }, */
                /* {
                    label: translate("ORDERS.PACKAGE_TYPE"),
                    key: "PackageType",
                    format: packageFormatter
                }, */
                {
                    label: translate("ORDERS.TO_CITY_NAME"),
                    key: "receiverVillageName"
                },
                {
                    label: translate("ORDERS.ORDER_DATE"),
                    key: "orderTime"
                },
                /* {
                    label: translate("ORDERS.DELIVERY_COST"),
                    key: "deliveryCost"
                }, */
                /* {
                    label: translate("ORDERS.ORDER_TIME"),
                    key: "TimeOrder"
                }, */
                /* {
                    label: translate("ORDERS.FROM_CITY"),
                    key: "FromAddress"
                }, */
                /* {
                    label: translate("ORDERS.CLIENT_NAME"),
                    key: "clientName"
                }, */
                /* {
                    label: translate("ORDERS.TO_CITY"),
                    key: "ToAddress"
                }, */
                {
                    label: translate("ORDERS.COD"),
                    key: "cod",
                    format: ({ cod }) => {
                        return <>{!!cod ? cod : 0}</>
                    }
                },
                {
                    label: translate("ORDERS.RECEIVER_NAME"),
                    key: "receiverName"
                },
                {
                    label: translate("ORDERS.ORDER_STATUS"),
                    key: "orderStatus",
                    format: statusFormat
                },
                {
                    label: translate("ORDERS.FULL_DETAILS"),
                    key: "orderId",
                    format: ({ orderId }) => showDetailsButton(orderId)
                }
            ];

            /* check reviewed */
            if (currentPage === "previous-orders" || currentPage === "reviewed-orders" /* && localStorage.getItem("userId") == 41 */) {
                newColumns.splice(0, 0, {
                    label: translate("ORDERS.REVIEWED"),
                    key: "assign",
                    format: ({ orderId, isReviewed, cod }, reviewed, handleCheck) => {
                        let loadCost = !!cod ? cod : 0;
                        return <input className="form-check-input" defaultChecked={currentPage === "previous-orders" ? (reviewed == 1 ? true : false) : (reviewed == 1 ? false : true)} style={{ cursor: "pointer" }} type="checkbox" value={orderId} id="flexCheckDefault" onClick={(event) => { /* checkOrderReviewedHandler(event.target.checked, event.target.value); */ addRemToReview(event.target.checked, event.target.value, loadCost); handleCheck() }} />
                    }
                });
            }

            if (currentPage !== "all-orders") {
                newColumns.splice(3, 0, {
                    label: translate("ORDERS.DELIVERY_COST"),
                    key: "deliveryCost"
                });

                newColumns.splice(1, 0, {
                    label: translate("ORDERS.FOREIGN_NUM"),
                    key: "foreignOrderBarcodea",
                    format: ({ foreignOrderId, foreignOrderBarcode }) => {
                        return <>{!!foreignOrderBarcode ? foreignOrderBarcode : !!foreignOrderId ? foreignOrderId : "-"}</>
                    }
                });
            }
        }

        /* edited (comment bidPrice) */
        /*if (isTransporter()) {
            newColumns.splice(3, 0, {
                label: translate("ORDER_DETAILS.BID_PRICE"),
                key: "bidPrice",
                format: ({idOrder}) => <p>{bidPrice}</p>
            });
        }*/

        if (!isTransporter() && currentPage === "all-orders") {
            newColumns.push({
                label: "",
                key: "",
                format: ({ idOrder }) => <CancelOrder className="w-100" socket={socket} orderId={idOrder} onSuccess={update} />
            });
        }

        /* edited (commented) */
        /* if (currentPage === "current-orders") {
            //TODO:: key to be changed

            newColumns.splice(3, 0, {
                label: translate("ORDERS.PRICE"),
                key: "CostLoad"
            });
            
            if(isTransporterMaster()){
                newColumns.splice(newColumns.length - 1, 0,{
                    label: translate("ORDER_DETAILS.ASSIGNED"),
                    key: "AssignedMemberName",
                });
            }
        } */ /* else if (currentPage === "previous-orders") {
            newColumns.push({
                label: translate("ORDERS.ORDER_STATUS"),
                key: "idOrder",
                format: ({IsFinished, IsDeleted}) => IsFinished ? translate("ORDERS.COMPLETED") : IsDeleted ? translate("ORDERS.CANCELED") : null
            })
        } */

        setColumns(newColumns);
    }, [currentPage, showDetailsButton]);

    return <div style={{ position: "relative" }}>
        <DynamicTable columns={columns} data={orders} currentPage={currentPage} />
        {reviewList.length > 0 && <div style={{ position: "absolute", bottom: "-30px", left: "20px" }}>
            <Button className="btn-grad" style={{ width: "200px" }} onClick={saveReviewed}>{currentPage === "previous-orders" ? "Reviewed" : "Remove"}</Button>
            <span className="ms-3" style={{ width: "200px" }}>Total COD: {totalCOD}</span>
        </div>}
    </div>;
};
