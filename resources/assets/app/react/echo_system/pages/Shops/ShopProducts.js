import React, {Component} from 'react';
import {connect} from 'react-redux';
import moment from 'moment';
import {DatetimePickerTrigger} from 'rc-datetime-picker';

import 'react-toastify/dist/ReactToastify.css';
import {getSession} from "../../../store/helper/helper";
import history from "../../../History";
import Header from "../../layout/Header";
import Loading from "../sub_components/Loading";
import {ToastContainer, toast} from 'react-toastify';
import ShopNav from "./_nav/ShopNav";
import Pagination from "../sub_components/Pagination";
import {_deleteProduct, _duplicateProduct, _fetchAllProduct, _saveProduct} from "../../../store/action/action-product";
import DataNotFound from "../sub_components/DataNotFound";
import Modal from "react-responsive-modal";

const queryString = require('query-string');

class ShopProducts extends Component {

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
            moment: moment(),
            alert: {
                show: false,
                detail: {
                    _is_confirm: false,
                    _purpose: null
                },
            },
            product: {
                id: '',
                title: '',
                description: '',
                price: '',
                status: 1,
                is_published: 1,
                dataUrl: '',
                upload: ''

            },
            filter: {
                filterName: '',
                filterValue: ''
            },
        };


        this.handleChange = this.handleChange.bind(this);
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
        this.props.fetchProducts(this.props.match.params.shop_id, this._builtQuery());
    }

    /**
     * handleChange
     * @param var event
     */
    handleChange(moment) {
        this.setState({
            moment
        });
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
        this.props.fetchProducts(this.props.match.params.shop_id, this._builtQuery());
    }

    /**
     * handleDeleteProduct
     * @param var _isOpen
     * @param object user
     * @param var is_confirm
     */
    handleDeleteProduct(_isOpen, selectedProduct = null, is_confirm = false) {
        if (selectedProduct !== null) {
            this.setState({
                ...this.state,
                product: {
                    id: selectedProduct.id,
                    title: selectedProduct.title,
                    description: selectedProduct.description,
                    price: selectedProduct.price,
                    status: selectedProduct.status,
                    is_published: selectedProduct.is_published,
                    upload: selectedProduct.upload

                },
            });
        }
        if (is_confirm !== false) {
            this.props.deleteProduct(this.props.match.params.shop_id, this.state.product);
            this.setState({
                ...this.state,
                product: {
                    id: '',
                    title: '',
                    description: '',
                    price: '',
                    status: '',
                    is_published: '',
                    upload: ''

                },
            });
        }
        if (_isOpen === true) {
            this.setState({alert: {show: true}});
        } else if (_isOpen === false) {
            this.setState({alert: {show: false}});
        }
    }

    /**
     * _shopList
     */
    _productList() {
        if (this.props.fetch_shop_product_props !== '') {
            if (this.props.fetch_shop_product_props.products.length === 0) {
                return DataNotFound({type: "table", colSpan: "7", message: "Uh-oh! there is no product available."})
            }
            return this.props.fetch_shop_product_props.products.map((product, index) => {
                return (
                    <tr key={index}>
                        <td>{product.title}</td>
                        <td>{product.description}</td>
                        <td>{"$" + product.price}</td>
                        <td align="center">{(product.is_published == 0) ? "No" : "Yes"}</td>
                        <td align="center">{(product.published_date) ? product.published_date : "-"}</td>
                        <td align="center">{(product.status) ? "Active" : "Inactive"}</td>
                        <td className='text-center'>
                            <a href="" className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item"
                                   href={"/admin/shop/" + this.props.match.params.shop_id + "/create_or_update/" + product.id}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>

                                <a className="dropdown-item"
                                   href={"/admin/shop/" + this.props.match.params.shop_id + "/product/" + product.id + "/variance/"}><i
                                    className='fa fa-plus-circle'></i> Add Variance</a>
                                <div className="dropdown-divider"></div>

                                <a className="dropdown-item">
                                    <i className='fa fa-clone'></i> Duplicate</a>

                                <div className="dropdown-divider"></div>

                                <a className="dropdown-item" onClick={() => this.handleDeleteProduct(true, product)}>
                                    <i className='fa fa-trash'></i> Delete</a>
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
                            <div className="col-3 col-md-3 mb-15 mt-15">
                                <div className="card">
                                    <div className="card-body">
                                        <ShopNav params={this.props.match}/>
                                    </div>
                                </div>

                            </div>
                            <div className="col-9 col-md-9">
                                <div className="regular-page-text mb-15 mt-15">
                                    <div className="regular-page-text">
                                        <div className="card">
                                            <div className="card-body">
                                                <h2>Menu</h2>
                                                <hr/>
                                                <a href={"/admin/shop/" + this.props.match.params.shop_id + "/create_or_update"}
                                                   className="btn btn-outline-dark font-14 mb-30 pull-right">Create
                                                    Product
                                                </a>
                                                <div className="clear-5"></div>
                                                <form className="mb-30">
                                                    <div className="row ">
                                                        <div className="col-md-2">
                                                            <select className="form-control" name="filterName"
                                                                    onChange={(e) => this.handleFilter(e)}>
                                                                <option value='filter_by'>Filter By</option>
                                                                <option value='title'>title</option>
                                                                <option value='description'>description</option>
                                                                <option value='price'>price</option>
                                                            </select>
                                                        </div>
                                                        <div className="col-md-4">
                                                            <input type="text" className="form-control"
                                                                   name="filterValue"
                                                                   onChange={(e) => this.handleFilter(e)}
                                                                   placeholder="Please Enter Query"/>
                                                        </div>
                                                        <div className="col-md-4">
                                                            <button type="button"
                                                                    className="btn btn-outline-dark font-14"
                                                                    onClick={(e) => this.handleSearch(e)}>
                                                                Search
                                                            </button>
                                                        </div>
                                                    </div>

                                                </form>
                                                <table className="table table-bordered mb-30">
                                                    <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Price</th>
                                                        <th>Publish status</th>
                                                        <th>Publish date</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this._productList()}

                                                    </tbody>
                                                </table>
                                                {this.props.fetch_shop_product_props.meta && this.props.fetch_shop_product_props.meta.pagination.total_pages > 1 &&
                                                <Pagination meta={this.props.fetch_shop_product_props.meta}
                                                            url={location.pathname}/>
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <Modal
                        open={this.state.alert.show}
                        onClose={() => this.handleDeleteProduct(false)}
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
                                                    Are you sure you want to delete (<b>{this.state.product.title}</b>)?
                                                </div>
                                                <div className="col-md-12 ">
                                                    <button type="button"
                                                            className="btn btn-outline-dark font-14 pull-right "
                                                            onClick={() => this.handleDeleteProduct(false, null, true)}>
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
        fetch_shop_product_props: state.product.fetch_products,
        error: state.error.error,
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({
        fetchProducts: (shop_id, params) => {
            dispatch(_fetchAllProduct(shop_id, params));
        },
        saveProduct: (shop_id, params) => {
            dispatch(_saveProduct(shop_id, params));
        },
        deleteProduct: (shop_id, params) => {
            dispatch(_deleteProduct(shop_id, params));
        },
        duplicateProduct: (shop_id, params) => {
            dispatch(_duplicateProduct(shop_id, params));
        },

    })
}

export default connect(mapStateToProp, mapDispatchToProp)(ShopProducts);
