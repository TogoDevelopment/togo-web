import translate from "../i18n/translate";
import React, { useEffect, useRef, useState } from "react";
import { Button, Col, Container, Form, Modal, Row, FloatingLabel, Spinner, ListGroup } from "react-bootstrap";
import {
    CreateAddressReq,
    GetCityRegion,
    GetCitiesArea,
    getLogestechsAreaByName,
    getALlLogestechsAreas,
    createExclusiveLogestechsAddress
} from "../APIs/OrdersAPIs";
import { useDispatch } from "react-redux";
import { toastNotification } from "../Actions/GeneralActions";
import './CreateAddress.css';

export default function CreateAddress({ onSuccess, children }) {

    let dispatch = useDispatch();

    const [loading, setLoading] = useState(false);
    const [validated, setValidated] = useState(false);

    const [isAreaValid, setIsAreaValid] = useState(true);

    const [show, setShow] = useState(false);

    const handleClose = () => {
        setShow(false);
    }

    const [regionId, setRegionId] = useState(0);
    const [cityId, setCityId] = useState(0);
    const [villageId, setVillageId] = useState(0);
    const [villageEnName, setVillageEnName] = useState("");
    const [villageArName, setVillageArName] = useState("");
    const [regionName, setRegionName] = useState("");

    const [loadingAreas, setLoadingAreas] = useState(false)
    const [logestechsAreas, setLogestechsAreas] = useState([]);

    /* const areaRef = useRef() */

    const [searchTerm, setSearchTerm] = useState('');
    let typingTimer;

    const handleInputChange = (e) => {
        setSearchTerm(e.target.value);
        setRegionId(0)
        setCityId(0)
        setVillageId(0)
        setVillageEnName("")
        setVillageArName("")
        setRegionName("")
    };

    const handleKeyDown = () => {
        // When a key is pressed, clear any existing timer
        clearTimeout(typingTimer);
    };

    const handleKeyUp = () => {
        setLoadingAreas(true)
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {

            // Implement your logic here, for example, making an API call
            getLogestechsAreaByName(searchTerm.trim()).then((res) => {
                // console.log(res.data)

                if (res.data.status == "error") {
                    console.log("error")
                } else {
                    if (!!!searchTerm) {
                        setLogestechsAreas([])
                    } else {
                        setLogestechsAreas(res.data.areas)
                    }
                }

                setLoadingAreas(false)
            })
        }, 2000); // 2000 milliseconds (2 seconds)
    };

    const selectAreaHandler = (area) => {
        // console.log(area)
        setSearchTerm((localStorage.getItem("Language") == "en" ? area.name : area.arabicName) + ", " + area.regionName)
        setLogestechsAreas([])

        setRegionId(area.regionId)
        setCityId(area.cityId)
        setVillageId(area.id)
        setVillageEnName(area.name)
        setVillageArName(area.arabicName)
        setRegionName(area.regionName)
    }

    return (
        <div>
            <span onClick={() => {
                setShow(true);
            }}>

                {children}
            </span>

            {/*  edited (fullscreen removed and style added) */}
            <Modal
                show={show}
                onHide={() => { setShow(false) }}
                centered
                animation={true}
                backdrop="static"
                size="lg"

                style={{ backgroundColor: "rgba(0,0,0,0.5)", }}
            >
                <Modal.Header closeButton className="card-header-lg">
                    <Modal.Title>{translate("ORDERS.ADD_ADDRESS")}</Modal.Title>
                </Modal.Header>
                <Modal.Body className="mt-5">
                    <div style={{ position: "relative" }}>
                        <FloatingLabel className="mb-3" controlId="userAddress" label={translate("CREATE_ADDRESS.SEARCH_AREA")}>
                            <Form.Control
                                className="input-inner-shadow"
                                type="text" placeholder="..."
                                /* name="logestechsArea" */
                                /* onChange={handleSearchArea} */
                                value={searchTerm}
                                onChange={handleInputChange}
                                onKeyDown={handleKeyDown}
                                onKeyUp={handleKeyUp}
                                autoComplete="off"
                                /* ref={areaRef} */
                                /* required */
                                isInvalid={!isAreaValid}
                            />
                        </FloatingLabel>

                        {(logestechsAreas.length > 0 || loadingAreas) && <div style={{
                            position: "absolute",
                            top: "55px",
                            left: "2px",
                            right: "2px",
                            maxHeight: "500px",
                            overflowY: "scroll",
                            zIndex: "3",
                            backgroundColor: "white",
                            border: "1px solid lightgray",
                            borderRadius: "0 0 10px 10px"
                        }}>
                            <ListGroup>
                                {
                                    loadingAreas ? <ListGroup.Item className="text-center"><Spinner size="lg" className="" animation="border" variant="dark" /></ListGroup.Item> :
                                        logestechsAreas.map((area) => (
                                            <ListGroup.Item className="area-list-item d-flex justify-content-between" key={area.id} onClick={() => { selectAreaHandler(area) }}>
                                                <div>{
                                                    (localStorage.getItem("Language") == "en" ? area.name : area.arabicName) + ", " + area.regionName
                                                }</div>
                                                <div className="select-area">
                                                    {translate("CREATE_ADDRESS.SELECT_AREA")}
                                                </div>
                                            </ListGroup.Item>
                                        ))

                                }
                            </ListGroup>
                        </div>}
                    </div>

                    <Form id="addressForm" validated={validated} noValidate /* ref={formRef} */ onSubmit={(event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        const formData = new FormData(event.target), formDataObj = Object.fromEntries(formData.entries());

                        let areaValidatiy = true;

                        // console.log(villageId + " - " + cityId + " - " + regionId)

                        if (!!villageId && !!cityId && !!regionId) {
                            // console.log("area selected")
                            setIsAreaValid(true);
                        } else {
                            // console.log("area not selected")
                            areaValidatiy = false;
                            setIsAreaValid(false);
                        }

                        const form = event.currentTarget;
                        if (form.checkValidity() === true && areaValidatiy === true) {

                            formDataObj.villageId = villageId
                            formDataObj.cityId = cityId
                            formDataObj.regionId = regionId
                            formDataObj.villageEnName = villageEnName
                            formDataObj.villageArName = villageArName
                            formDataObj.regionName = regionName
                            
                            /* console.log(formDataObj); // addressDetails, addressAditionalInfo, contactPhone, contactName, villageId, cityId, regionId, villageName, regionName
                            return; */

                            setLoading(true);
                            createExclusiveLogestechsAddress(formDataObj).then((res) => {
                                console.log(res.data)
                                
                                if (res.data.status === "error") {
                                    dispatch(toastNotification("Error!", "Something went wrong!", "error"));
                                    console.log(res.data.error)
                                } else {
                                    dispatch(toastNotification("Address Created!", "Address Created Successfully", "success"));
                                    handleClose();
                                    onSuccess();
                                }

                                setLoading(false);
                            });
                        }

                        setValidated(true);

                    }}>

                        <FloatingLabel className="mb-3" controlId="formBasicEmail" label={translate("ORDERS.ADDRESS_NAME")}>
                            <Form.Control className="input-inner-shadow" type="text" placeholder="..." name="contactName" required />
                            <Form.Control.Feedback type="invalid">
                                Please add place name
                            </Form.Control.Feedback>
                        </FloatingLabel>

                        <FloatingLabel className="mb-3" controlId="userTel" label={translate("ORDERS.ADDRESS_PHONE")}>
                            <Form.Control className="input-inner-shadow" type="tel" placeholder="..." name="contactPhone" pattern="^05[0-9]{8}?$" required />
                            <Form.Control.Feedback type="invalid">
                                Please enter a valid phone number example 0568000000
                            </Form.Control.Feedback>
                        </FloatingLabel>

                        <FloatingLabel className="mb-3" controlId="userAddress" label={translate("ORDERS.ADDRESS")}>
                            <Form.Control className="input-inner-shadow" type="text" placeholder="..." name="addressDetails" required />
                        </FloatingLabel>

                        <FloatingLabel className="mb-3" controlId="addressInfo" label={translate("ORDERS.ADDRESS_INFO")}>
                            <Form.Control className="input-inner-shadow" type="text" placeholder="..." name="addressAditionalInfo" />
                        </FloatingLabel>

                        <hr className="mt-3 mb-4" />

                        <div style={{ float: "right" }}>
                            <Button variant="outline-primary" type="submit">
                                {loading && <Spinner size="sm" className="me-1" animation="border" variant="light" />}

                                {translate("GENERAL.PROCEED")}
                            </Button>

                            {"  "}
                            <Button variant="danger" onClick={handleClose}>
                                {translate("GENERAL.CANCEL")}
                            </Button>
                        </div>
                    </Form>
                </Modal.Body>
            </Modal>
        </div>
    )
}