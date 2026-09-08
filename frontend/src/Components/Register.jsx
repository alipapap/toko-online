import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import api from "../api/axios";

export default function Register() {
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      const res = await api.post("/register", form);
      localStorage.setItem("token", res.data.token);
      localStorage.setItem("user", JSON.stringify(res.data.user));
      navigate("/");
    } catch (err) {
      if (err.response?.data?.errors) {
        const firstError = Object.values(err.response.data.errors)[0][0];
        setError(firstError);
      } else {
        setError("Terjadi kesalahan, coba lagi.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[70vh] d-flex align-items-center justify-content-center py-5">
      <div className="w-100" style={{ maxWidth: "28rem" }}>

        <div className="text-center mb-4">
          <h1 className="fw-bold">Buat Akun</h1>
          <p className="text-secondary">Daftar untuk mulai berbelanja di TokoKita.</p>
        </div>

        <div className="bg-white rounded-4 shadow-sm border p-4 p-md-5">
          {error && (
            <div className="alert alert-danger" role="alert">
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit}>
            {/* NAMA */}
            <div className="mb-4">
              <label htmlFor="name" className="form-label fw-semibold">
                Nama Lengkap
              </label>
              <input
                id="name"
                type="text"
                name="name"
                className="form-control form-control-lg rounded-3"
                value={form.name}
                onChange={handleChange}
                placeholder="Nama lengkap"
                required
                autoFocus
              />
            </div>

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

            {/* KONFIRMASI PASSWORD */}
            <div className="mb-4">
              <label htmlFor="password_confirmation" className="form-label fw-semibold">
                Konfirmasi Password
              </label>
              <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                className="form-control form-control-lg rounded-3"
                value={form.password_confirmation}
                onChange={handleChange}
                placeholder="Ulangi password"
                required
              />
            </div>

            <button
              type="submit"
              className="btn btn-primary btn-lg w-100 rounded-pill"
              disabled={loading}
            >
              {loading ? "Memproses..." : "Buat Akun"}
            </button>
          </form>

          <div className="text-center mt-4 pt-3 border-top">
            <span className="text-secondary">Sudah punya akun?</span>{" "}
            <Link to="/login" className="text-primary fw-semibold text-decoration-none">
              Login
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}