import streamlit as st
from main import chat_inference

# Sidebar
with st.sidebar:
    st.header("🚫 mulai ulang")
    st.markdown("mulai ulang sesi kamu.")
    
    if st.button("🔄 Reset Percakapan"):
        st.session_state.messages = []
        st.success("Percakapan telah direset.")
        st.rerun()

# Judul utama
st.title("🤖 Pokebot - Asisten Pokémon Pintar")

st.markdown("""
## Selamat datang di Pokebot! 👋

Pokebot adalah asisten AI cerdas yang siap membantu kamu menjelajahi dunia Pokémon.  
Tanyakan apa saja seputar Pokémon—mulai dari karakter, evolusi, hingga strategi pertempuran!

💬 **Contoh pertanyaan yang bisa kamu coba:**
- "Apa itu Pikachu?"
- "Bagaimana cara menangkap Bulbasaur?"
- "Apa kelemahan Charizard?"

ℹ️ *Berikan pertanyaan yang jelas dan spesifik untuk hasil terbaik.*
""")

# Inisialisasi session state jika belum ada
if "messages" not in st.session_state:
    st.session_state.messages = []

# Menampilkan riwayat chat
for message in st.session_state.messages:
    with st.chat_message(message["role"]):
        st.markdown(message["content"])

# Input chat dari user
prompt = st.chat_input("Tanyakan sesuatu tentang Pokémon...")
if prompt:
    response = chat_inference(prompt)

    with st.chat_message("user"):
        st.markdown(prompt)
    st.session_state.messages.append({"role": "user", "content": prompt})

    with st.chat_message("assistant"):
        st.markdown(response)
    st.session_state.messages.append({"role": "assistant", "content": response})
