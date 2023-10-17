import { Button, Dropdown, Form, Modal, Spinner, Table, Card, Container, Row, Col, ListGroup, FloatingLabel, Badge } from "react-bootstrap";
import React, { useEffect, useRef, useState } from "react";
import translate from "../../i18n/translate";
import { ReactComponent as SendIcon } from "../../assets/images/send.svg";
import {
    food_getCustomers,
    food_getClientAreas
} from "../../APIs/OrdersAPIs";
import { GetTransporterClients } from "../../APIs/OrdersAPIs"
import { ReactComponent as FoodIcon } from "../../assets/images/food.svg";
import { ReactComponent as SmBoxIcon } from "../../assets/images/smallBox.svg";
import { ReactComponent as MedBoxIcon } from "../../assets/images/medbox.svg";
import { ReactComponent as BigBoxIcon } from "../../assets/images/largebox.svg";
import { IoMdAdd } from 'react-icons/io';
import { ReactComponent as DeliveryTruckIcon } from "../../assets/images/deliveryTruck.svg";
import { ReactComponent as LocationIcon } from "../../assets/images/location.svg";
import { ReactComponent as AttachmentIcon } from "../../assets/images/attachment.svg";
import { DeliveryTypes, PackageTypes } from "../Orders/OrdersTabularView";
import ClientDropdown from "../ClientDropdown";
import { AddIcon } from "@chakra-ui/icons";
import { useIntl } from "react-intl";
import { useDispatch } from "react-redux";
import { toastNotification } from "../../Actions/GeneralActions";
import { isTransporter } from "../../Util";
import "../CreateNewOrder.css";
import { useHistory } from "react-router";
import CreateAddress from "../CreateAddress";
import { imgBaseUrl } from "../../Constants/GeneralCont";

export const PackageTypesIcons = {
    "1": FoodIcon,
    "2": SmBoxIcon,
    "3": MedBoxIcon,
    "4": BigBoxIcon
};

export default function CreateNewFoodOrderCo(props) {

    const history = useHistory();
    let dispatch = useDispatch();
    const intl = useIntl();

    const [packageType, setPackageType] = useState("1");
    const [customers, setCustomers] = useState([]);
    const [searchedCustomers, setSearchedCustomers] = useState([]);
    const [loadingCustomers, setLoadingCustomers] = useState(false);
    const [selectedCustomerAddressId, setSelectedCustomerAddressId] = useState({});
    const [showAddCustomerOption, setShowAddCustomerOption] = useState(false);
    const [showAddCustomerModal, setShowAddCustomerModal] = useState(false);
    const [restaurantAreas, setRestaurantAreas] = useState([]);

    const customerPhoneRef = useRef();

    useEffect(() => {
        setLoadingCustomers(true);

        food_getCustomers().then((res) => {

            if (res.data.status === "error") {
                dispatch(toastNotification("Error!", translate("CREATE_NEW_ORDER.WENT_WRONG"), "error"));
            } else {
                // console.log(res.data.customers);
                setCustomers(res.data.customers);
                setSearchedCustomers(res.data.customers);
            }

            setLoadingCustomers(false);
        })
    }, [])

    const filterCustomerHandler = (subNumber) => {
        const filteredCustomers = customers.filter(customer => customer.phone.includes(subNumber));

        // check if number is 10 digits(valid number) to show add-new customer option
        const phonePattern = /^0\d{9}$/;

        if (phonePattern.test(subNumber)) {
            setShowAddCustomerOption(true);
        } else {
            setShowAddCustomerOption(false);
        }

        setSearchedCustomers(filteredCustomers);
    }

    const selectCustomerHandler = (customerAddressId) => {

        // console.log(customerAddressId);

        let tempSelectedCustomer = customers.find(customer => customer.id === customerAddressId);

        // console.log(tempSelectedCustomer);

        setSelectedCustomerAddressId(tempSelectedCustomer);
    }

    const deselectCustomerHandler = (customerAddressId) => {

        setSelectedCustomerAddressId({});
    }

    const handleShowAddCustomerModal = () => {
        setShowAddCustomerModal(true);
    }

    const handleCloseAddCustomerModal = () => {
        setShowAddCustomerModal(false);
    }

    const getRestaurantAreas = () => {
        food_getClientAreas().then((res) => {
            if (res.data.status == "error") {
                dispatch(toastNotification("Error!", translate("CREATE_NEW_ORDER.WENT_WRONG"), "error"));
            } else {
                // console.log(res.data.areas);

                const resArr = res.data.areas;
                const tempArr = [];

                resArr.forEach((area) => {
                    tempArr.push({ "id": area.areaId, "name": area.areaName, "description": area.description, "price": area.price, selected: false });
                });

                console.log(tempArr);

                setRestaurantAreas(tempArr);
            }
        });
    }

    const handleChooseArea = (areaId) => {

        const updatedAreas = restaurantAreas.map((item) => {
            if (item.id === areaId) {
                return { ...item, selected: true };
            } else {
                return { ...item, selected: false };
            }
        });

        setRestaurantAreas(updatedAreas);
    }

    return (
        <>
            {/* upper background */}
            <div className="upperBackground">
            </div>

            <div className="mainContainer" style={{ height: "80%", top: "150px" }}>
                <Container fluid className="pb-5">
                    <Row className="h2 d-flex justify-content-center" style={{ marginTop: "50px", marginBottom: "0px", fontWeight: "bold", color: "white" }}>
                        {translate("CREATE_NEW_ORDER.CREATE_ORDER")}
                    </Row>

                    {/* ------------------( order info )------------------ */}
                    <Row className="mb-5">
                        <Col>
                            {/* <Card className="rounded-3 shadow"> */}
                            {/*  <Card.Header style={styles.cardHeaderLg}>
                                    <Card.Title className="mt-2">{translate("CREATE_NEW_ORDER.ORDER_INFO")}</Card.Title>
                                </Card.Header> */}

                            <Form id="orderForm" /* validated={validated} */ noValidate onSubmit={(event) => {
                                
                            }}>
                                <ListGroup variant="flush" className="mt-5 customListGroup">

                                    {/* ------------------( search for a customer by phone number & add new customer )------------------ */}
                                    <ListGroup.Item className="py-4">
                                        <div className="container-fluid">
                                            <div className="row">
                                                <div className="mb-3 h5" style={{ color: "#26a69a" }}>
                                                    {translate("CREATE_NEW_ORDER.SEARCH_CUSTOMER")}
                                                </div>

                                                <div className="col-lg-4 w-100">
                                                    <div className="row pb-3" style={{ borderBottom: "2px solid lightgray" }}>
                                                        <div className="col-12 d-flex justify-content-center">
                                                            <Form.Control
                                                                type="number"
                                                                className="shadow w-50"
                                                                placeholder={intl.formatMessage({ id: "CREATE_NEW_ORDER.CUSTOMER_PHONE" })}
                                                                onChange={(e) => { filterCustomerHandler(e.target.value) }}
                                                                ref={customerPhoneRef}
                                                            />
                                                            {/* showAddCustomerOption */true && <Button
                                                                className="btn-grad ms-3"
                                                                onClick={() => { handleShowAddCustomerModal(); getRestaurantAreas() }}
                                                            >
                                                                <IoMdAdd />
                                                            </Button>}
                                                        </div>
                                                    </div>

                                                    <div className="row mb-3">
                                                        {Object.keys(selectedCustomerAddressId).length !== 0 && <div>
                                                            <Table className="w-100 mt-2">
                                                                {/* <thead>
                                                                    <tr>
                                                                        <th>{translate("CREATE_NEW_ORDER.CUSTOMER_NAME")}</th>
                                                                        <th>{translate("CREATE_NEW_ORDER.CUSTOMER_PHONE")}</th>
                                                                        <th>{translate("CREATE_NEW_ORDER.CUSTOMER_AREA")}</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead> */}
                                                                <tbody>
                                                                    <tr style={{ backgroundColor: "#C7EEDD" }}>
                                                                        <td>{selectedCustomerAddressId.name}</td>
                                                                        <td>{selectedCustomerAddressId.phone}</td>
                                                                        <td>{selectedCustomerAddressId.area}</td>
                                                                        <td>
                                                                            <Button
                                                                                variant="danger"
                                                                                onClick={deselectCustomerHandler}
                                                                            >
                                                                                {translate("CREATE_NEW_ORDER.DESELECT")}
                                                                            </Button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </Table>
                                                        </div>}
                                                    </div>

                                                    <div className="row">
                                                        <div className="col-12" style={{ height: "300px", overflowY: "scroll" }}>
                                                            <Table striped className="w-100 mt-2">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{translate("CREATE_NEW_ORDER.CUSTOMER_NAME")}</th>
                                                                        <th>{translate("CREATE_NEW_ORDER.CUSTOMER_PHONE")}</th>
                                                                        <th>{translate("CREATE_NEW_ORDER.CUSTOMER_AREA")}</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                {!loadingCustomers ? <tbody>
                                                                    {
                                                                        searchedCustomers?.map((customer, index) => {
                                                                            return <tr key={index}>
                                                                                <td>{customer.name}</td>
                                                                                <td>{customer.phone}</td>
                                                                                <td>{customer.area}</td>
                                                                                <td>
                                                                                    <Button
                                                                                        className="btn-grad"
                                                                                        onClick={() => selectCustomerHandler(customer.id)}
                                                                                    >
                                                                                        {translate("CREATE_NEW_ORDER.SELECT")}
                                                                                    </Button>
                                                                                </td>
                                                                            </tr>
                                                                        })
                                                                    }
                                                                </tbody> : <tbody>
                                                                    <tr><td colSpan={4}><Spinner animation="border" size="sm" /></td></tr>
                                                                </tbody>}
                                                            </Table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </ListGroup.Item>

                                    {/* ------------------( cod )------------------ */}
                                    <ListGroup.Item className="py-4" style={{ backgroundColor: "#ededed" }}>
                                        <div className="container-fluid">
                                            <div className="d-flex align-items-center mb-3 h5" style={{ color: "#26a69a" }}>
                                                <DeliveryTruckIcon style={{ width: "20px", height: "20px" }} className="me-1" />
                                                {translate("CREATE_NEW_ORDER.ENTER_COD")}
                                            </div>

                                            <div className="row">
                                                <div className="col-lg-12">
                                                    test
                                                </div>
                                            </div>
                                        </div>
                                    </ListGroup.Item>

                                    {/* ------------------( prep-time )------------------ */}
                                    <ListGroup.Item className="py-4">
                                        <div className="container-fluid">
                                            <div className="d-flex align-items-center mb-3 h5" style={{ color: "#26a69a" }}>
                                                <AttachmentIcon style={{ width: "20px", height: "20px" }} className="me-1" />
                                                {translate("CREATE_NEW_ORDER.PREP_TIME")}
                                            </div>

                                            <div className="row">
                                                <div className="col-lg-12">
                                                    test
                                                </div>
                                            </div>
                                        </div>
                                    </ListGroup.Item>

                                    {/* ------------------( validation )------------------ */}
                                    <ListGroup.Item className="py-4" style={{ backgroundColor: "#ededed" }}>
                                        <div className="container-fluid">
                                            {/* showError */false && <span style={{ color: "#d9534f" }}>
                                                <i className="bi bi-info-circle"></i>{" "}
                                                {translate("CREATE_NEW_ORDER.REQUIRED_ERROR")}
                                            </span>}
                                            {true ? <Button
                                                className="btn-grad"
                                                style={{ width: "30%", float: "right" }}
                                                disabled={/* loadingSubmit */false}
                                                type="submit"
                                            >
                                                {/* loadingSubmit */false && <Spinner animation="border" size="sm" />}
                                                {translate("CREATE_NEW_ORDER.SUBMIT_ORDER")}
                                            </Button> : <span className="h6" style={{ color: "#26a69a", float: "right" }}>{translate("TEMP.SUCCISSFULLY_PUBLISHED_CLIENT")}</span>}
                                        </div>
                                    </ListGroup.Item>

                                </ListGroup>
                            </Form>
                            {/* </Card> */}
                        </Col>
                    </Row>

                </Container>
            </div>

            <Modal size="xl" show={showAddCustomerModal} onHide={handleCloseAddCustomerModal}>
                <Modal.Header closeButton>
                    <Modal.Title>{translate("CREATE_NEW_ORDER.ADD_NEW_CUSTOMER")} ({customerPhoneRef.current?.value})</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Form onSubmit={(e) => {
                        e.preventDefault();

                        e.preventDefault();
                        e.stopPropagation();

                        const formData = new FormData(e.target), formDataObj = Object.fromEntries(formData.entries());

                        console.log('Customer Name:', formDataObj.customerName);

                        // Close the modal
                        handleCloseAddCustomerModal();
                    }}>
                        <div className="d-flex justify-content-between">
                            <Form.Group className="mb-3" style={{ width: "48%" }} controlId="exampleForm.ControlInput1">
                                <Form.Label>{translate("CREATE_NEW_ORDER.CUSTOMER_NAME")}: <sub>({translate("CREATE_NEW_ORDER.OPTIONAL")})</sub></Form.Label>
                                <Form.Control
                                    type="text"
                                    placeholder="name..."
                                    autoFocus
                                    name="customerName"
                                />
                            </Form.Group>
                            <Form.Group className="mb-3" style={{ width: "48%" }} controlId="exampleForm.ControlInput1">
                                <Form.Label>{translate("CREATE_NEW_ORDER.DESCRIPTION")}: <sub>({translate("CREATE_NEW_ORDER.OPTIONAL")})</sub></Form.Label>
                                <Form.Control
                                    type="text"
                                    placeholder="description..."
                                    autoFocus
                                    name="customerDescription"
                                />
                            </Form.Group>
                        </div>
                        {translate("CREATE_NEW_ORDER.CUSTOMER_AREA")}:
                        <div className="grid-container">
                            {
                                restaurantAreas?.map((area, index) => {
                                    return <div
                                        key={index}
                                        className="grid-item"
                                        style={area.selected ? { border: "1px solid #43ba9f", backgroundColor: "#DBFFF7" } : {}}
                                        onClick={() => handleChooseArea(area.id)}
                                    >
                                        <h4 className="h4" style={{ color: "#43ba9f" }}>{area.name}</h4>
                                        <hr />
                                        <p className="mt-2">{/* {translate("CREATE_NEW_ORDER.DESCRIPTION")}:  */}{!!!area.description ? "---" : area.description}</p>
                                        <p className="h5 mt-2">{area.price} NIS</p>
                                    </div>
                                })
                            }
                            <div className="grid-item d-flex justify-content-center align-items-center">
                                <h1 className="h1"><IoMdAdd /></h1>
                            </div>
                        </div>
                        <div className="d-flex justify-content-center pt-3">
                            <Button className="me-2 w-25" variant="secondary" onClick={handleCloseAddCustomerModal}>
                                {translate("CREATE_NEW_ORDER.CANCEL")}
                            </Button>
                            <Button type="submit" className="ms-3 w-25 btn-grad">
                                {translate("CREATE_NEW_ORDER.ADD_NEW_CUSTOMER")}
                            </Button>
                        </div>
                    </Form>
                </Modal.Body>
            </Modal>
        </>
    )
}