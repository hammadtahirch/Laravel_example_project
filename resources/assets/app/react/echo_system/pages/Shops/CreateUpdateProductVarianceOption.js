import React, {Component} from 'react';
import {connect} from 'react-redux';
import moment from 'moment';
import {DatetimePickerTrigger} from 'rc-datetime-picker';

import 'react-toastify/dist/ReactToastify.css';
import {getSession} from "../../../store/helper/helper";
import {ToastContainer, toast} from 'react-toastify';
import ValidationErrors from "../sub_components/ValidationErrors";
import history from "../../../History";
import Loading from "../sub_components/Loading";
import Header from "../../layout/Header";
import ShopNav from "./_nav/ShopNav";
import Pagination from "../sub_components/Pagination";
import Modal from "react-responsive-modal";
import {
    _deleteProductVarianceOption,
    _fetchAllProductVarianceOptions,
    _saveProductVarianceOption
} from "../../../store/action/action-product-variance-option";
import {convertDollarToCent} from "../../../store/helper/utill-helper";

const queryString = require('query-string');

class CreateUpdateProductVarianceOption extends Component {

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
            alert: {
                show: false,
                detail: {
                    _is_confirm: false,
                    _purpose: null
                },
            },
            option: {
                id: '',
                title: '',
                price: '',
                variance_id: '',
            },
            filter: {
                filterName: '',
                filterValue: ''
            },
        };

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
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
        this.props.fetchProductVarianceOptions(this.props.match.params.variance_id, this._builtQuery());
    }

    /**
     *
     * @param nextProps
     * @param prevState
     */
    componentWillReceiveProps(nextProps, prevState) {
        console.log(nextProps);
        if (nextProps.getSavedProductVarianceOptionProps !== '') {

            this.setState({
                option: {
                    ...this.state,
                    id: nextProps.getSavedProductVarianceOptionProps.option.id,
                    title: nextProps.getSavedProductVarianceOptionProps.option.title,
                    price: nextProps.getSavedProductVarianceOptionProps.option.price
                }
            })
            toast.success("Wow! Variance Save Successfully.")
        }

        if (nextProps.getDeletedProductVarianceOptionProps !== '') {
            this.setState({
                option: {
                    ...this.state,
                    id: nextProps.getDeletedProductVarianceOptionProps.option.id,
                    title: nextProps.getDeletedProductVarianceOptionProps.option.title,
                    price: nextProps.getDeletedProductVarianceOptionProps.option.price
                }
            })
            toast.success("Wow! Variance delete Successfully.")
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
     *
     */
    handleChange(e) {
        const {name, value} = e.target;
        const {option} = this.state;
        this.setState({
            option: {
                ...option,
                [name]: value
            }
        });
    }

    /**
     * save data in storage
     *
     * @param option
     */
    saveUpdateOption(option) {
        debugger;
        option = {
            ...option,
            price: convertDollarToCent(option.price)
        }
        option.variance_id = this.props.match.params.variance_id;
        this.props.saveUpdateProductVarianceOption(option.variance_id, option);
    }

    editOption(selectedOption) {
        debugger;
        this.setState({
            ...this.state,
            option: {
                id: selectedOption.id,
                title: selectedOption.title,
                price: selectedOption.price
            }
        });
        window.scrollTo(0, 0)
    }

    /**
     * handleDeleteOption
     * @param var _isOpen
     * @param object user
     * @param var is_confirm
     */
    handleDeleteOption(_isOpen, selectedOption = null, is_confirm = false) {
        if (selectedOption !== null) {
            this.setState({
                ...this.state,
                option: {
                    id: selectedOption.id,
                    title: selectedOption.title,
                    price: selectedOption.price
                }
            });
        }
        if (is_confirm !== false) {
            this.props.deleteProductVarianceOption(this.props.match.params.variance_id, this.state.option);
            this.setState({
                ...this.state,
                option: {
                    id: '',
                    title: '',
                    product_id: '',
                    max_permitted: '',
                    min_permitted: '',
                    description: ''
                }
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
    _productOptionList() {
        if (this.props.getProductVarianceOptionProps !== '') {
            if (this.props.getProductVarianceOptionProps.options.length === 0) {
                return DataNotFound({type: "table", colSpan: "7", message: "Uh-oh! there is no option available."})
            }
            return this.props.getProductVarianceOptionProps.options.map((option, index) => {
                return (
                    <tr key={index}>
                        <td>{option.title}</td>
                        <td>{option.price}</td>
                        <td className='text-center'>
                            <a className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item"
                                   onClick={() => this.editOption(option)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>
                                <a className="dropdown-item"
                                   onClick={() => this.handleDeleteOption(true, option)}><i
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
                                        <div className="card mb-15">
                                            <div className="card-body">
                                                <h5> Product Options </h5>
                                                <hr/>
                                                {(this.props.error !== "") &&
                                                <ValidationErrors validationErrors={this.props.error.data}
                                                                  statusCode={this.props.error.status}/>
                                                }
                                                <form>
                                                    <div className="row ">
                                                        <div className="col-md-6 mb-3">
                                                            <label>Title<span>*</span></label>
                                                            <input type="text" className="form-control"
                                                                   value={this.state.option.title}
                                                                   name="title" onChange={(e) => this.handleChange(e)}/>
                                                        </div>
                                                        <div className="col-md-6 mb-3">
                                                            <label>Price<span>*</span></label>
                                                            <input type="text" className="form-control"
                                                                   name="price" onChange={(e) => this.handleChange(e)}
                                                                   value={this.state.option.price}/>
                                                        </div>
                                                        <div className="col-md-12 mb-3">
                                                            <button type="button"
                                                                    className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                                    onClick={() => this.saveUpdateOption(this.state.option)}>
                                                                {(this.state.option.id) ? "Update" : "Create"}
                                                            </button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div className="card">
                                            <div className="card-body">
                                                <h2>Options</h2>
                                                <hr/>
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
                                                        <th>price</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this._productOptionList()}

                                                    </tbody>
                                                </table>
                                                {this.props.fetchProductVarianceOptions.meta && this.props.fetchProductVarianceOptions.meta.pagination.total_pages > 1 &&
                                                <Pagination meta={this.props.fetchProductVarianceOptions.meta}
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
                        onClose={() => this.handleDeleteoption(false)}
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
                                                    Are you sure you want to delete (<b>{this.state.option.title}</b>)?
                                                </div>
                                                <div className="col-md-12 ">
                                                    <button type="button"
                                                            className="btn btn-outline-dark font-14 pull-right "
                                                            onClick={() => this.handleDeleteOption(false, null, true)}>
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
        getProductVarianceOptionProps: state.product_variance_option.fetch_product_variance_options,
        getSavedProductVarianceOptionProps: state.product_variance_option.save_product_variance_option,
        getDeletedProductVarianceOptionProps: state.product_variance_option.delete_product_variance_option,
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
        fetchProductVarianceOptions: (variance_id, params) => {
            dispatch(_fetchAllProductVarianceOptions(variance_id, params));
        },
        saveUpdateProductVarianceOption: (variance_id, params) => {
            dispatch(_saveProductVarianceOption(variance_id, params));
        },
        deleteProductVarianceOption: (variance_id, params) => {
            dispatch(_deleteProductVarianceOption(variance_id, params));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(CreateUpdateProductVarianceOption);
