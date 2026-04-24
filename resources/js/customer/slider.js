import Swiper from "swiper";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { Autoplay, Navigation, Pagination } from "swiper/modules";

Swiper.use([Navigation, Pagination, Autoplay]);

window.Swiper = Swiper;
