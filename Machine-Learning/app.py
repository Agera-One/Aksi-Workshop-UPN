import streamlit as st
from main import chat_inference

st.title("Chatbot sederhana meggunakan Gemini API")

if "messages" not in st.session_state:
    st.session_state.messages = []

for message in st.session_state.messages:
    with st.chat_message(message["role"]):
        st.markdown(message["content"])

prompt = st.chat_input("Masukkan pertanyaan Anda:")
if prompt:
    response = chat_inference(prompt)
    
    with st.chat_message("user"):
        st.markdown(prompt)

    st.session_state.messages.append({
        "role": "user", "content": prompt
    })

    with st.chat_message("assistant"):
        st.markdown(response)
    
    st.session_state.messages.append({
        "role": "assistant", "content": prompt
    })


# prompt = st.chat_input("Masukkan pertanyaan Anda:")
# st.chat_message("user", prompt)
# if prompt:
#     response = chat_inference(prompt)
#     with st.chat_message("assistant"):
#         st.markdown(response)
