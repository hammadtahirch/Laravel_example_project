import axios from 'axios';
import Config from "../config/app-constants";


export function filterGeoLocationComponent(response) {
    if (response.length > 0) {
        let GeoLocation = {};
        let AddressComponent = filterAddressComponent(response[0].address_components);
        let GeoCoordinates = filterCoordinates(response[0].geometry);

        GeoLocation = {
            addressComponent: AddressComponent,
            geoCoordinates: GeoCoordinates,
            formattedAddress: response[0].formatted_address

        };

        return GeoLocation;
    } else {
        return [];
    }

    console.log(response);

}

export function filterAddressComponent(AddressComponent) {
    let addressComponent = {};

    AddressComponent.map(function (value, index) {
        switch (value.types[0]) {
            case "locality":
                addressComponent.city = {long_name: value.short_name, short_name: value.short_name};
                break;
            case "administrative_area_level_1":
                addressComponent.province = {long_name: value.short_name, short_name: value.short_name};
                break;
            case "country":
                addressComponent.country = {long_name: value.short_name, short_name: value.short_name};
                break;
            case "postal_code":
                addressComponent.postal_code = {long_name: value.short_name, short_name: value.short_name};
                break;

        }
    });

    return addressComponent;
}

export function filterCoordinates(coordinates) {
    let local = {
        latitude: coordinates.location.lat(),
        longitude: coordinates.location.lng(),
    }

    return local;

}