import React, {Component} from 'react';
import {connect} from 'react-redux';
import moment from 'moment';
import {DatetimePickerTrigger} from 'rc-datetime-picker';

import 'react-toastify/dist/ReactToastify.css';
import {getSession} from "../../../store/helper/helper";
import {ToastContainer, toast} from 'react-toastify';
import {
    _deleteProductVariance,
    _fetchAllProductVariances, _saveProductVariance,

} from "../../../store/action/action-product-variance";
import ValidationErrors from "../sub_components/ValidationErrors";
import history from "../../../History";
import Loading from "../sub_components/Loading";
import Header from "../../layout/Header";
import ShopNav from "./_nav/ShopNav";
import Pagination from "../sub_components/Pagination";
import Modal from "react-responsive-modal";

const queryString = require('query-string');

class CreateUpdateProductVariance extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);
        if (getSession('login') === null) {
            history.push('login');
        }
        this.myRef = React.createRef()
        this.state = {
            alert: {
                show: false,
                detail: {
                    _is_confirm: false,
                    _purpose: null
                },
            },
            variance: {
                id: '',
                title: '',
                product_id: '',
                max_permitted: '',
                min_permitted: '',
                description: '',
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
        this.props.fetchProductVariances(this.props.match.params.product_id, this._builtQuery());
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
        const {variance} = this.state;
        this.setState({
            variance: {
                ...variance,
                [name]: value
            }
        });
    }

    /**
     * save data in storage
     *
     * @param variance
     */
    saveUpdateVariance(variance) {
        variance.product_id = this.props.match.params.product_id;
        this.props.saveUpdateProductVariance(variance.product_id, variance);
    }

    editVariance(selectedVariance) {
        this.setState({
            ...this.state,
            variance: {
                id: selectedVariance.id,
                title: selectedVariance.title,
                product_id: selectedVariance.product_id,
                max_permitted: selectedVariance.max_permitted,
                min_permitted: selectedVariance.min_permitted,
                description: selectedVariance.description
            }
        });
        this.myRef.current.scrollTo(0, 0);
    }

    /**
     * handleDeleteVariance
     * @param var _isOpen
     * @param object user
     * @param var is_confirm
     */
    handleDeleteVariance(_isOpen, selectedVariance = null, is_confirm = false) {
        if (selectedVariance !== null) {
            this.setState({
                ...this.state,
                variance: {
                    id: selectedVariance.id,
                    title: selectedVariance.title,
                    product_id: this.props.match.params.product_id,
                    max_permitted: selectedVariance.max_permitted,
                    min_permitted: selectedVariance.min_permitted,
                    description: selectedVariance.description
                }
            });
        }
        if (is_confirm !== false) {
            this.props.deleteProductVariance(this.props.match.params.product_id, this.state.variance);
            this.setState({
                ...this.state,
                variance: {
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
    _productVarianceList() {
        if (this.props.getProductVarianceProps !== '') {
            if (this.props.getProductVarianceProps.variances.length === 0) {
                return DataNotFound({type: "table", colSpan: "7", message: "Whoops! there is no variance available."})
            }
            return this.props.getProductVarianceProps.variances.map((variance, index) => {
                return (
                    <tr key={index}>
                        <td>{variance.title}</td>
                        <td>{variance.description}</td>
                        <td>{variance.max_permitted}</td>
                        <td>{variance.min_permitted}</td>
                        <td className='text-center'>
                            <a className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item"
                                   onClick={() => this.editVariance(variance)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>

                                <a className="dropdown-item"
                                   href={"/admin/shop/" + this.props.match.params.id + "/variance/" + this.props.match.params.variance_id + "/create_update_variance_option/" + variance.id}><i
                                    className='fa fa-plus-circle'></i> Add Options</a>
                                <div className="dropdown-divider"></div>
                                <a className="dropdown-item"
                                   onClick={() => this.handleDeleteVariance(true, variance)}><i
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
                                            <div className="card-body" ref={this.myRef}>
                                                <h5>{(this.state.variance.id === '') ? "Create Variance" : "Update Variance"}</h5>
                                                <hr/>
                                                <form>
                                                    <div className="row ">
                                                        <div className="col-md-4">
                                                            <label>Title<span>*</span></label>
                                                            <input type="text" className="form-control"
                                                                   name="title" value={this.state.variance.title}
                                                                   onChange={(e) => this.handleChange(e)}/>
                                                        </div>

                                                        <div className="col-md-4 mb-3">
                                                            <label> Maximum Permitted<span>*</span></label>
                                                            <input type="text" className="form-control"
                                                                   name="max_permitted"
                                                                   value={this.state.variance.max_permitted}
                                                                   onChange={(e) => this.handleChange(e)}/>
                                                        </div>
                                                        <div className="col-md-4 mb-3">
                                                            <label>Minimum Permitted <span>*</span></label>
                                                            <input type="text" className="form-control"
                                                                   name="min_permitted"
                                                                   value={this.state.variance.min_permitted}
                                                                   onChange={(e) => this.handleChange(e)}/>
                                                        </div>
                                                        <div className="col-md-12 mb-3">
                                                            <label>Description<span>*</span></label>
                                                            <textarea className="form-control"
                                                                      name="description"
                                                                      value={this.state.variance.description}
                                                                      onChange={(e) => this.handleChange(e)}>

                                                            </textarea>
                                                        </div>
                                                        <div className="col-md-12">
                                                            <button type="button"
                                                                    onClick={() => this.saveUpdateVariance(this.state.variance)}
                                                                    className="btn btn-outline-dark font-14 pull-right">
                                                                {(this.state.variance.id === '') ? "Create" : "Update"}
                                                            </button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div className="card">
                                            <div className="card-body">
                                                <h2>Product Variance</h2>
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
                                                        <th>Description</th>
                                                        <th>Max Permitted</th>
                                                        <th>min Permitted</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this._productVarianceList()}

                                                    </tbody>
                                                </table>
                                                {this.props.fetchProductVariances.meta && this.props.fetchProductVariances.meta.pagination.total_pages > 1 &&
                                                <Pagination meta={this.props.fetchProductVariances.meta}
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
                        onClose={() => this.handleDeleteVariance(false)}
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
                                                    Are you sure you want to delete (<b>{this.state.variance.title}</b>)?
                                                </div>
                                                <div className="col-md-12 ">
                                                    <button type="button"
                                                            className="btn btn-outline-dark font-14 pull-right "
                                                            onClick={() => this.handleDeleteVariance(false, null, true)}>
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
        getProductVarianceProps: state.product_variance.fetch_product_variances,
        getSavedProductVarianceProps: state.product_variance.save_product_variance,
        getDeletedProductVarianceProps: state.product_variance.delete_product_variance,
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
        fetchProductVariances: (product_id, params) => {
            dispatch(_fetchAllProductVariances(product_id, params));
        },
        saveUpdateProductVariance: (product_id, params) => {
            dispatch(_saveProductVariance(product_id, params));
        },
        deleteProductVariance: (product_id, params) => {
            dispatch(_deleteProductVariance(product_id, params));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(CreateUpdateProductVariance);
