import { Link, useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";
import axios from "../api/axios";

export default function Layout({ children }) {
  const navigate = useNavigate();
  const token = localStorage.getItem("token");
  const user = JSON.parse(localStorage.getItem("user") || "null");
  const [cartCount, setCartCount] = useState(0);

  const fetchCartCount = () => {
    if (!token) return;
    axios
      .get("/cart")
      .then((res) => {
        const items = res.data.items ?? [];
        const total = items.reduce((sum, item) => sum + item.quantity, 0);
        setCartCount(total);
      })
      .catch(() => setCartCount(0));
  };

  useEffect(() => {
    fetchCartCount();

    window.addEventListener("cart-updated", fetchCartCount);
    return () => window.removeEventListener("cart-updated", fetchCartCount);
  }, [token]);

  const handleLogout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    navigate("/login");
  };

  return (
    <div className="products-page">

      <nav className="products-navbar">
        <Link to="/" className="products-logo">
          TokoKita
        </Link>

        <div className="products-navlinks">
          <Link to="/products">Produk</Link>
          <Link to="/orders">Pesanan</Link>
        </div>

        <div className="products-navactions">
          <Link to="/cart" className="btn-cart position-relative">
            Keranjang
            {cartCount > 0 && (
              <span
                style={{
                  position: "absolute",
                  top: "-6px",
                  right: "-6px",
                  background: "#ef4444",
                  color: "white",
                  borderRadius: "50%",
                  width: "20px",
                  height: "20px",
                  fontSize: "11px",
                  fontWeight: 700,
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                }}
              >
                {cartCount}
              </span>
            )}
          </Link>

          {token && user ? (
            <>
              <span className="nav-username">{user.name}</span>
              <button className="btn-logout" onClick={handleLogout}>
                Logout
              </button>
            </>
          ) : (
            <>
              <Link to="/login" className="nav-btn-login">Login</Link>
              <Link to="/register" className="nav-btn-register">Daftar</Link>
            </>
          )}
        </div>
      </nav>

      {children}

    </div>
  );
}