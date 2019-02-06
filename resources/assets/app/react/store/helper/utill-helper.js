import axios from 'axios';
import Config from "../config/app-constants";
import history from "../../History";

/**
 * helper function to get the name of the day
 *
 * @param dayNumber
 * @returns {string}
 */
export function getDayName(dayNumber) {
    let day = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    return day[dayNumber - 1];
}