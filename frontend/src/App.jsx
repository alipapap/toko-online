import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./Components/Login";
import Register from "./Components/Register";
import './App.css';
import Home from "./Components/Home/Home";
import Products from "./Components/Pages/Products";
import ProductDetail from "./Components/Pages/ProductDetail";
import Cart from "./Components/Pages/Cart";
import Checkout from "./Components/Pages/Checkout";
import Payment from "./Components/Pages/Payment";
import Orders from "./Components/Pages/Orders";
import OrderDetail from "./Components/Pages/OrderDetail";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/products" element={<Products />} />
        <Route path="/products/:id" element={<ProductDetail />} />
        <Route path="/cart" element={<Cart />} />
        <Route path="/checkout" element={<Checkout />} />
        <Route path="/payment/:orderId" element={<Payment />} />
        <Route path="/orders" element={<Orders />} />
        <Route path="/orders/:orderId" element={<OrderDetail />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;