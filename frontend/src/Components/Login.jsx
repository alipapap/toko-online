import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import api from "../api/axios";

export default function Login() {
  const [form, setForm] = useState({ email: "", password: "", remember: false });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setForm({ ...form, [name]: type === "checkbox" ? checked : value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      const res = await api.post("/login", form);
      localStorage.setItem("token", res.data.token);
      localStorage.setItem("user", JSON.stringify(res.data.user));
      navigate("/");
    } catch (err) {
      if (err.response?.data?.errors) {
        const firstError = Object.values(err.response.data.errors)[0][0];
        setError(firstError);
      } else {
        setError("Email atau password salah.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[70vh] d-flex align-items-center justify-content-center py-5">
      <div className="w-100" style={{ maxWidth: "28rem" }}>

        <div className="text-center mb-4">
          <h1 className="fw-bold">Selamat Datang</h1>
          <p className="text-secondary">Login untuk melanjutkan ke TokoKita.</p>
        </div>

        <div className="bg-white rounded-4 shadow-sm border p-4 p-md-5">
          {error && (
            <div className="alert alert-danger" role="alert">
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit}>
            {/* EMAIL */}
            <div className="mb-4">
              <label htmlFor="email" className="form-label fw-semibold">
                Email
              </label>
              <input
                id="email"
                type="email"
                name="email"
                className="form-control form-control-lg rounded-3"
                value={form.email}
                onChange={handleChange}
                placeholder="nama@email.com"
                required
                autoFocus
              />
            </div>

            {/* PASSWORD */}
            <div className="mb-4">
              <label htmlFor="password" className="form-label fw-semibold">
                Password
              </label>
              <input
                id="password"
                type="password"
                name="password"
                className="form-control form-control-lg rounded-3"
                value={form.password}
                onChange={handleChange}
                placeholder="Masukkan password"
                required
              />
            </div>

            {/* REMEMBER */}
            <div className="form-check mb-4">
              <input
                id="remember"
                type="checkbox"
                name="remember"
                className="form-check-input"
                checked={form.remember}
                onChange={handleChange}
              />
              <label htmlFor="remember" className="form-check-label text-secondary">
                Ingat saya
              </label>
            </div>

            <button
              type="submit"
              className="btn btn-primary btn-lg w-100 rounded-pill"
              disabled={loading}
            >
              {loading ? "Memproses..." : "Login"}
            </button>
          </form>

          <div className="text-center mt-4 pt-3 border-top">
            <span className="text-secondary">Belum punya akun?</span>{" "}
            <Link to="/register" className="text-primary fw-semibold text-decoration-none">
              Daftar sekarang
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}