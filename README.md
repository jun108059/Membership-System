<p align="center">
    <img width="300px" src="/img/Logo.PNG" align="center" alt="Membership" />
    <h2 align="center">PHP membership system</h2>
    <p align="center">It is a Membership system that implements only essential functions!</p>
</p>

<p align="center">
    <a href="https://github.com/jun108059/Membership-System">
        <img alt="HTML" src="https://img.shields.io/badge/-HTML-orange?logo=HTML5" />
    </a>
    <a href="https://github.com/jun108059/Membership-System">
      <img alt="CSS" src="https://img.shields.io/badge/-CSS-blue?logo=CSS3" />
    </a>
    <a href="https://github.com/jun108059/Membership-System">
       <img alt="JS" src="https://img.shields.io/badge/-JavaScript-CC9900?logo=JavaScript" />
    </a>
    <a href="https://github.com/jun108059/Membership-System">
        <img alt="PHP" src="https://img.shields.io/badge/-PHP-9B59B6?logo=PHP" />
    </a>
    <a href="https://github.com/jun108059/Membership-System">
        <img alt="MySQl" src="https://img.shields.io/badge/-MySQL-85C1E9?logo=MySQL" />
    </a>
    <a href="https://github.com/jun108059/Membership-System">
        <img alt="NGINX" src="https://img.shields.io/badge/-NGINX-green?logo=NGINX" />
    </a>           
    <br />
    <br />
    <a href="https://hits.seeyoufarm.com">
        <img src="https://hits.seeyoufarm.com/api/count/incr/badge.svg?url=https%3A%2F%2Fgithub.com%2Fjun108059%2FMembership-System"/>
    </a>
</p>

<p align="center">
    <a href="/demo/">View Demo</a>
</p>

---

## Membership Service

![img](/img/UseCase-Diagram.PNG)


## ⚙Setting

> 💡 **Not Use** Web Server Stack Installer Package   
> **Just** Download `Nginx` And `PHP` And `MySQL`

1. NGINX Download
    - [Download Link](http://nginx.org/en/download.html)

2. PHP Download
    - [Download Link](https://windows.php.net/download)
    - In my case, install `PHP` inside the `NGINX` folder.

3. MySQL Download
    - [Download Link](https://dev.mysql.com/downloads/mysql/)
    - Install and Configuration : [Reference](https://devpad.tistory.com/21)

4. Modify NGINX configuration file
    - [View Setting file](/nginx/conf/nginx.conf)
    - modify `fastcgi_param SCRIPT_FILENAME` ~~
    - In my case, fastcgi_param SCRIPT_FILENAME `C:/nginx/html$` fastcgi_script_name;
    - add `location / {... index ... index.php;}`

5. Setting `php.ini` file
    - [View Setting file](/nginx/php7/php.ini)
    - Change `php.ini-development` file to `php.ini`
    - Check `extension_dir = "C:\nginx\php7\ext"` (~758 line~)
    - Check `Dynamic Extensions` (~910-934 line~)

5. Run `php-cgi`
    - Run `cmd`
    - Change directory to php folder 
        - In my case, `C:\nginx\php7`
    - Enter Command : `php-cgi -b 127.0.0.1:9000`

6. Execute `NGINX` Server
    - Run `cmd`
    - Change directory to NGINX folder 
        - In my case, `C:\nginx`
    - Enter Command : `nginx`

> Can register the php as a service and have it run automatically.

--- 

## 📂 Directory structure
``` bash
MVC
  |-App                             ### MemberShip App
  |  |-Controllers                  ## 🕹Controllers
  |  |  |-AdminController.php       # About Admin
  |  |  |-DormantController.php     # About Dormant
  |  |  |-...                       # About Home, Login, etc.
  |  |-Models                       ## 🛢Models
  |  |  |-Admin.php                 # About Admin
  |  |  |-...                       # About Home, Login, etc.
  |  |-Service                      ## 📧Service
  |  |  |-MailerService.php         # About SendMail,
  |  |  |-...                       # send to Dormant, etc.
  |  |-Views                        ## ✨Views
  |  |  |-Admin                     # About Admin
  |  |  |...                        # About Membership, Error, etc.
  |-Core                            ### MVC System Core
  |  |-Controller.php               # Magic Method call
  |  |-Model.php                    # Connect DB
  |  |-Router.php                   # Parameter Routing
  |  |-View.php                     # Rendering
  |-public                          ### Public Library
  |  |-bootstrap                    # Front css, js
  |  |-css                          # css
  |  |-index.php                    # 💡Front Controller
  |-vender                          ### Third party
  |  |-composer                     # composer
  |  |-phpmailer                    # for Mail
  |  |-autoload.php                 # autoload
  |-composer.json                   # composer
```

### ✔️사용자 Section

- 회원가입
- 로그인 + 로그아웃
- 아이디/비밀번호 찾기
- 개인정보 수정
- 회원 탈퇴
- ⭐휴면 계정 해제

### ✔️관리자 Section

- 관리자 로그인
- 회원 정보 list 검색
- 정보 상세보기
- 회원 강제 탈퇴

---

### ✔️사용자 Web page

![img](/img/Page-사용자.PNG)

### ✔️관리자 Web page

![img](/img/Page-관리자.PNG)

---

## DB Table 설계

![img](/img/DB-Table-설계.PNG)

---

## WBS
`Work Breakdown Structure` 작성

![img](/img/WBS최종.PNG)

---

## 🎉 기술 스택

![img](/img/devStack.PNG)

---

## 🧱 개발 환경

- Windows10
- Nginx 1.18.0
- MySQL 5.7.30
- PHP 7.3.18
- HeidiSQL 11.0.0
- PhpStorm 2020.1.2

---

## 🎈 필요한 공부

✔ Web 시스템

- [Web Client Side 공부](https://github.com/jun108059/Web-Study/tree/master/Client-side)
- [Web Server Side 공부](https://github.com/jun108059/Web-Study)
- [게시판 만들기](Study/bulletin-board)
- [Ajax 비동기 처리](Study/ajax_json)
---

> **🏁 목표**  
> 1. 프레임워크 없이  
> 2. 기본적인 구조부터 이해하고
> 3. 기능만 잘 구현할 것
> - 웹 사이트 공통 필수 모듈의 개발 및 설계 능력 향상
> - 세션/쿠키에 대한 이해
> - 개인정보 암호화 기법에 대한 이해
> - UI기획 + 설계, DB 설계
