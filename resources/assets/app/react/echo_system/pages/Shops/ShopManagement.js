import React, {Component} from 'react';
import {connect} from 'react-redux';

import {getSession, filterGeoLocationComponent} from "../../../store/helper/helper";

import history from "../../../History";
import Header from "../../layout/Header";
import Pagination from "../sub_components/Pagination";
import Loading from "../sub_components/Loading";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import {_fetchAllShop, _saveShop, _deleteShop} from "../../../store/action/action-shop";
import Modal from "react-responsive-modal";
import SuggestionInput from "../sub_components/SuggestionInput";
import {_fetchAllUser} from "../../../store/action/action-acounts";
import PlacesAutocomplete, {
    geocodeByAddress,
    getLatLng,
} from 'react-places-autocomplete';

const queryString = require('query-string');

class ShopManagement extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);

        if (getSession('login') === null) {
            history.push('login');
        }

        this.state = {
            modal: {
                show: false,
            },
            address: '',
            alert: {
                show: false,
                detail: {
                    _is_confirm: false,
                    _purpose: null
                },
            },
            _suggestions: {},
            shop: {
                id: '',
                user_id: '',
                title: '',
                description: '',
                address: '',
                city: '',
                province: '',
                country: '',
                portal_code: '',
                latitude: '',
                longitude: '',
                user: {
                    id: '',
                    name: '',
                    email: ''
                }

            },
            filter: {
                filterName: '',
                filterValue: ''
            },
            error: '',

        };
    }


    /**
     * componentWillMount [react default life cycle functions]
     */
    componentWillMount() {
        this.props.fetch_shop_list(this._builtQuery());
    }

    /**
     * componentWillReceiveProps [react default life cycle functions]
     * @param NextProps
     */
    componentWillReceiveProps(NextProps) {
        this.setState({"error": NextProps.error});
        this.setState({"_suggestions": NextProps.fetch_users})
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {

    }

    /**
     * _builtQuery
     */
    _builtQuery() {
        let fill = {};
        if (this.state.filter.filterName !== '' && this.state.filter.filterValue !== '') {

            fill[this.state.filter.filterName] = this.state.filter.filterValue
            return queryString.parse(location.search + queryString.stringify(fill))
        }
        else {
            return queryString.parse(location.search)
        }
    }

    /**
     * handleChange
     * @param var event
     */
    handleChange(event) {
        const {name, value} = event.target;
        const {shop} = this.state;
        this.setState({
            shop: {
                ...shop,
                [name]: value
            }
        });
    }

    /**
     * handleChange
     * @param var event
     */
    handleSelectUser(event) {
        const {name, value} = event.target;
        const {user} = this.state;
        this.setState({
            user: {
                ...user,
                [name]: value
            }
        });
    }

    /**
     * handleFilter
     * @param var event
     */
    handleFilter(event) {
        const {name, value} = event.target;
        const {filter} = this.state;
        this.setState({
            filter: {
                ...filter,
                [name]: value
            }
        });
    }

    /**
     * handleSearch
     */
    handleSearch() {
        this.props.fetch_shop_list(this._builtQuery());
    }

    /**
     * handleIsModelOpen
     * @param var _isOpen
     */
    handleIsModelOpen(_isOpen) {
        if (_isOpen === true) {
            this.setState({modal: {show: true}});
        } else if (_isOpen === false) {
            this.setState({modal: {show: false}});
            this.setState(
                {
                    shop: {
                        id: '',
                        user_id: '',
                        title: '',
                        description: '',
                        address: '',
                        city: '',
                        province: '',
                        country: '',
                        portal_code: '',
                        latitude: '',
                        longitude: '',
                        user: {
                            id: '',
                            name: '',
                            email: ''
                        }

                    },
                    error: ''
                }
            );
        }
    }

    /**
     * handleDeleteShop
     * @param var _isOpen
     * @param object user
     * @param var is_confirm
     */
    handleDeleteShop(_isOpen, shop = null, is_confirm = false) {
        if (shop !== null) {
            this.setState({
                shop: {
                    id: shop.id,
                    user_id: shop.user_id,
                    title: shop.title,
                    description: shop.description,
                    address: shop.address,
                    city: shop.city,
                    province: shop.province,
                    country: shop.country,
                    portal_code: shop.portal_code,
                    latitude: shop.latitude,
                    longitude: shop.longitude,
                    user: {
                        id: shop.user.id,
                        name: shop.user.name,
                        email: shop.user.email
                    }

                },
            });
        }
        if (is_confirm !== false) {
            this.props.delete_shop(this.state.shop);
            setTimeout(
                function () {
                    this.props.fetch_shop_list(this._builtQuery());
                    toast.success("Congratulation! Shop deleted successfully.");
                }
                    .bind(this),
                100);

        }
        if (_isOpen === true) {
            this.setState({alert: {show: true}});
        } else if (_isOpen === false) {
            this.setState({alert: {show: false}});
        }
    }

    /**
     * handleEditUser
     * @param Object user
     */
    handleEditShop(shop) {
        this.setState(
            {
                shop: {
                    id: shop.id,
                    user_id: shop.user_id,
                    title: shop.title,
                    description: shop.description,
                    address: shop.address,
                    city: shop.city,
                    province: shop.province,
                    country: shop.country,
                    portal_code: shop.portal_code,
                    latitude: shop.latitude,
                    longitude: shop.longitude,
                    user: {
                        id: shop.user.id,
                        name: shop.user.name,
                        email: shop.user.email
                    }

                },
            }
        );
        this.handleIsModelOpen(true);
    }

    /**
     * handleModalSave
     */
    handleModalSave(shop) {
        this.props.save_shop(shop);
        setTimeout(
            function () {
                if (this.state.error == '') {
                    toast.success("Congratulation! shop Saved successfully.");
                    this.setState(
                        {
                            shop: {
                                id: '',
                                user_id: '',
                                title: '',
                                description: '',
                                address: '',
                                city: '',
                                province: '',
                                country: '',
                                portal_code: '',
                                latitude: '',
                                longitude: '',
                                user: {
                                    id: '',
                                    name: '',
                                    email: ''
                                }

                            },
                        }
                    );
                    this.props.fetch_shop_list(this._builtQuery());
                    this.handleIsModelOpen(false);

                }
            }
                .bind(this),
            1000);


    }

    /**
     * _handleKeyUpSuggestions
     */
    handleKeyUpSuggestions(e) {
        this.props.fetch_user_list(queryString.parse(queryString.stringify({
            email: e.target.value,
            role_id: 5,
            _render: "list"
        })));
    }

    /**
     * _handleOnClickSuggestions
     */
    handleOnClickSuggestions(user) {
        this.setState({
                shop: {
                    ...this.state.shop,
                    user_id: user.id,
                    user: {
                        id: user.id,
                        name: user.name,
                        email: user.email
                    }
                },
                _suggestions: ''
            }
        );
    }

    /**
     * handleChangeAddress
     */
    handleChangeAddress(address) {
        this.setState({
            shop: {
                ...this.state.shop,
                address: address,
            }
        });
    };

    /**
     * handleSelectAddress
     */
    handleSelectAddress(address) {
        var self = this;
        let geoLocation
        geocodeByAddress(address)
            .then(function (response) {
                geoLocation = filterGeoLocationComponent(response);
                if (geoLocation != "") {
                    setTimeout(function () {
                        self.setState({
                            address: geoLocation.formattedAddress,
                            shop: {
                                ...self.state.shop,
                                address: geoLocation.formattedAddress,
                                city: geoLocation.addressComponent.city.long_name,
                                province: geoLocation.addressComponent.province.long_name,
                                country: geoLocation.addressComponent.country.long_name,
                                portal_code: geoLocation.addressComponent.postal_code.long_name,
                                latitude: geoLocation.geoCoordinates.latitude,
                                longitude: geoLocation.geoCoordinates.longitude
                            }
                        });
                    }, 100)

                }
            })
            .catch(error => console.error('Error', error));
    }

    /**
     * _shopList
     */
    _shopList() {
        if (this.props.fetch_shops !== '') {
            return this.props.fetch_shops.shops.map((shop, index) => {
                return (
                    <tr key={index}>
                        <td>{shop.id}</td>
                        <td>{shop.title}</td>
                        <td>{shop.description}</td>
                        <td>{shop.address}</td>
                        <td>{shop.user.email}</td>
                        <td className='text-center'>
                            <a href="" className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item" onClick={() => this.handleEditShop(shop)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>

                                <a className="dropdown-item" href={"shop/" + shop.id + "/time_slot"}><i
                                    className="fa fa-cogs"></i> Shop Settings</a>
                                <div className="dropdown-divider"></div>

                                <a className="dropdown-item" onClick={() => this.handleDeleteShop(true, shop)}><i
                                    className='fa fa-trash'></i> Delete</a>
                            </div>
                        </td>
                    </tr>
                )
            })

        }
    }

    /**
     * render [DOM render ]
     */
    render() {
        const modalStyle = {
            modal: {
                maxWidth: "500px",
            }
        }
        return (

            <div>
                <ToastContainer/>
                <Loading/>
                <Header/>

                <div className="single-blog-wrapper">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-12 col-md-12">
                                <div className="regular-page-content-wrapper clear-10">
                                    <div className="regular-page-text">
                                        <h2>Shop Management</h2>
                                        <button className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                onClick={() => this.handleIsModelOpen(true)}>Create Shop
                                        </button>
                                        <div className="clear-5"></div>
                                        <form className="mb-30">
                                            <div className="row ">
                                                <div className="col-md-2">
                                                    <select className="form-control" name="filterName"
                                                            onChange={(e) => this.handleFilter(e)}>
                                                        <option value='filter_by'>Filter By</option>
                                                        <option value='name'>Name</option>
                                                        <option value='email'>Email</option>
                                                        <option value='phone_number'>Phone Number</option>
                                                    </select>
                                                </div>
                                                <div className="col-md-4">
                                                    <input type="text" className="form-control" name="filterValue"
                                                           onChange={(e) => this.handleFilter(e)}
                                                           placeholder="Please Enter Query"/>
                                                </div>
                                                <div className="col-md-4">
                                                    <button type="button" className="btn btn-outline-dark font-14"
                                                            onClick={(e) => this.handleSearch(e)}>
                                                        Search
                                                    </button>
                                                </div>
                                            </div>

                                        </form>

                                        <table className="table table-bordered mb-30">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Address</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {this._shopList()}

                                            </tbody>
                                        </table>
                                        {this.props.fetch_shops.meta && this.props.fetch_shops.meta.pagination.total_pages > 1 &&
                                        <Pagination meta={this.props.fetch_shops.meta}
                                                    url={location.pathname}/>
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Modal
                    open={this.state.modal.show}
                    onClose={() => this.handleIsModelOpen(false)}
                    closeOnEsc={false}
                    closeOnOverlayClick={false}
                    styles={modalStyle}>

                    <div className="container">
                        <div className="row">
                            <div className="col-12 col-md-12">
                                <div className="checkout_details_area mt-50 clearfix">

                                    <div className="cart-page-heading mb-30">
                                        {
                                            (this.state.shop.id == '' || this.state.shop.id == null) ?
                                                <h5>Create Shop</h5> : <h5>Update Shop</h5>
                                        }
                                    </div>

                                    <form>
                                        <div className="row ">
                                            <pre>{JSON.stringify(this.state.error)}</pre>

                                            <SuggestionInput
                                                lable={"Select User Account"}
                                                suggestions={(this.state._suggestions == '') ? '' : this.state._suggestions.users}
                                                onClick={(user) => this.handleOnClickSuggestions(user)}
                                                onKeyUp={(e) => this.handleKeyUpSuggestions(e)}
                                                value={this.state.shop.user.email}
                                                placeholder={"Search User"}
                                            />
                                            <div className="col-md-12 mb-3">
                                                <label>Title<span>*</span></label>
                                                <input type="text" className="form-control" name="title"
                                                       value={this.state.shop.title}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-12 mb-3">
                                                <label>Description <span>*</span></label>
                                                <input type="text" className="form-control" name="description"
                                                       value={this.state.shop.description}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-12 mb-3">
                                                <label>Address <span>*</span></label>
                                                <PlacesAutocomplete
                                                    value={this.state.shop.address}
                                                    onChange={(address) => this.handleChangeAddress(address)}
                                                    onSelect={(address) => this.handleSelectAddress(address)}
                                                >
                                                    {({getInputProps, suggestions, getSuggestionItemProps, loading}) => (
                                                        <div>
                                                            <input
                                                                {...getInputProps({
                                                                    placeholder: 'Search Places ...',
                                                                    className: 'form-control',
                                                                })}
                                                            />
                                                            <ul className="list-group user-autoCompelete">
                                                                {loading &&
                                                                <li className="list-group-item">Loading...</li>}
                                                                {suggestions.map(suggestion => {
                                                                    const className = suggestion.active
                                                                        ? 'list-group-item suggestion-item--active'
                                                                        : 'list-group-item suggestion-item';
                                                                    // inline style for demonstration purpose
                                                                    const style = suggestion.active
                                                                        ? {
                                                                            backgroundColor: '#fafafa',
                                                                            cursor: 'pointer'
                                                                        }
                                                                        : {
                                                                            backgroundColor: '#ffffff',
                                                                            cursor: 'pointer'
                                                                        };
                                                                    return (
                                                                        <li
                                                                            {...getSuggestionItemProps(suggestion, {
                                                                                className,
                                                                                style,
                                                                            })}
                                                                        >
                                                                            <span>{suggestion.description}</span>
                                                                        </li>
                                                                    );
                                                                })}
                                                            </ul>
                                                        </div>
                                                    )}
                                                </PlacesAutocomplete>

                                                {/*<input type="text" className="form-control" name="address"*/}
                                                {/*value={this.state.shop.address}*/}
                                                {/*onChange={(e) => this.handleChange(e)}/>*/}
                                            </div>
                                            <div className="col-md-6 mb-3">
                                                <label>City <span>*</span></label>
                                                <input type="text" className="form-control" name="city"
                                                       value={this.state.shop.city}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-6 mb-3">
                                                <label>Province <span>*</span></label>
                                                <input type="text" className="form-control" name="province"
                                                       value={this.state.shop.province}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-6 mb-3">
                                                <label>Country <span>*</span></label>
                                                <input type="text" className="form-control" name="country"
                                                       value={this.state.shop.country}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-6 mb-3">
                                                <label>Postal Code <span>*</span></label>
                                                <input type="text" className="form-control" name="postal_code"
                                                       value={this.state.shop.portal_code}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>

                                            <div className="col-md-12 mb-3">
                                                <button type="button"
                                                        className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                        onClick={() => this.handleModalSave(this.state.shop)}>
                                                    {
                                                        (this.state.shop.id == '' || this.state.shop.id == null) ? 'Create' : 'Update'
                                                    }
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </Modal>
                <div>
                    <Modal
                        open={this.state.alert.show}
                        onClose={() => this.handleDeleteShop(false)}
                        closeOnEsc={false}
                        closeOnOverlayClick={false}
                        styles={{maxWidth: "1000px"}}>

                        <div className="container">
                            <div className="row">
                                <div className="col-12 col-md-12">
                                    <div className="checkout_details_area mt-15 clearfix">

                                        <div className="cart-page-heading mb-10">
                                            <h5>Alert</h5>
                                        </div>
                                        <form>
                                            <div className="row ">

                                                <div className="col-md-12 mb-10">
                                                    Are you sure you want to delete (<b>{this.state.shop.title}</b>)?
                                                </div>
                                                <div className="col-md-12 ">
                                                    <button type="button"
                                                            className="btn btn-outline-dark font-14 pull-right "
                                                            onClick={() => this.handleDeleteShop(false, null, true)}>
                                                        Proceed
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </Modal>
                </div>
            </div>

        );
    }
}

/**
 * mapStateToProp
 * @param  state
 * @return states
 */
function mapStateToProp(state) {
    return ({
        fetch_shops: state.shop.fetch_shops,
        fetch_users: state.account.fetch_users,
        error: state.account.error,
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({
        fetch_shop_list: (params) => {
            dispatch(_fetchAllShop(params));
        },
        fetch_user_list: (params) => {
            dispatch(_fetchAllUser(params));
        },
        save_shop: (params) => {
            dispatch(_saveShop(params));
        },
        delete_shop: (params) => {
            dispatch(_deleteShop(params));
        },
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(ShopManagement);
