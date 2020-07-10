# 🛡️ Membership 구현

> **🏁 목표**  
> 1. 프레임워크 없이  
> 2. 기본적인 구조부터 이해하고
> 3. 기능만 잘 구현할 것
> - 웹 사이트 공통 필수 모듈의 개발 및 설계 능력 향상
> - 세션/쿠키에 대한 이해
> - 개인정보 암호화 기법에 대한 이해
> - UI기획+설계, DB 설계

---

## 1. Task 관리

### ✔️사용자 Section

- 회원가입
- 로그인 + 로그아웃
- 아이디/비밀번호 찾기
- 개인정보 수정
- 회원 탈퇴
- ⭐휴면 계정 해제

### ✔️관리자 Section
- 회원 정보 list 검색
- 정보 상세보기
- 회원 강제 탈퇴

---

## 2. WebSite FlowChart

### ✔️사용자 Web page

![img](img/Web-Users.PNG)

### ✔️관리자 Web page

![img](img/Web-Admin.PNG)


## 3. DB Table 설계

**멤버십 Table 구성 정보**  
`id`, `비밀번호`, `이름`, `생년월일`, `이메일`, `휴대폰번호`, `주소`

- 각 기능 별 SubTask 나눠보기
- 데이터베이스 설계
- 설계한 이후 WBS 작성

---

## 3. WBS
Work Breakdown Structure 작성


---

## 🧱 개발 환경

- Windows10
- Nginx 1.18.0
- MySQL 5.7.30
- PHP 7.3.18
- HeidiSQL 11.0.0
- PhpStorm 2020.1.2
