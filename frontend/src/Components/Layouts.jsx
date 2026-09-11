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
    <div>
      <nav className="navbar navbar-expand bg-white border-bottom py-3">
        <div className="container-fluid px-4">
          <Link to="/" className="navbar-brand fw-bold text-primary fs-4">
            TokoKita
          </Link>

          <div className="d-flex gap-4 ms-4 me-auto">
            <Link to="/products" className="text-dark text-decoration-none">
              Produk
            </Link>
            <Link to="/orders" className="text-dark text-decoration-none">
              Pesanan
            </Link>
          </div>

          <div className="d-flex align-items-center gap-3">
            <Link
              to="/cart"
              className="btn btn-primary rounded-pill position-relative"
            >
              Keranjang
              {cartCount > 0 && (
                <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {cartCount}
                </span>
              )}
            </Link>

            {token && user ? (
              <>
                <span className="fw-semibold text-secondary">{user.name}</span>
                <button
                  className="btn btn-outline-danger rounded-pill"
                  onClick={handleLogout}
                >
                  Logout
                </button>
              </>
            ) : (
              <>
                <Link to="/login" className="btn btn-primary rounded-pill">
                  Login
                </Link>
                <Link
                  to="/register"
                  className="btn btn-outline-primary rounded-pill"
                >
                  Daftar
                </Link>
              </>
            )}
          </div>
        </div>
      </nav>

      {children}
    </div>
  );
}