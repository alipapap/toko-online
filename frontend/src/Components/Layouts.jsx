import { Link, useNavigate } from "react-router-dom";

export default function Layout({ children }) {
  const navigate = useNavigate();
  const token = localStorage.getItem("token");
  const user = JSON.parse(localStorage.getItem("user") || "null");

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
          <button className="btn-cart">Keranjang</button>

          {token && user ? (
            <>
              <span className="nav-username">{user.name}</span>
              <button className="btn-logout" onClick={handleLogout}>
                Logout
              </button>
            </>
          ) : (
            <>
              <Link to="/login" className="btn-cart">Login</Link>
              <Link to="/register" className="btn-logout">Daftar</Link>
            </>
          )}
        </div>
      </nav>

      {children}

    </div>
  );
}