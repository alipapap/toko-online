import { Link } from "react-router-dom";

export default function Layout({ children }) {
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
          <span className="nav-username">alifatul</span>
          <button className="btn-logout">Logout</button>
        </div>
      </nav>

      {children}

    </div>
  );
}