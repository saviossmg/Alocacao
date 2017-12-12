<?php
/**
 * VwAluno
 *
 * @Table(name="VwAluno", indexes={@Index(name="fk_VwAluno_VwCurso1_idx", columns={"Curso"})})
 * @Entity
 */
class Vwaluno
{
    /**
     * @var integer
     *
     * @Column(name="Id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="Nome", type="string", length=100, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @Column(name="Matricula", type="string", length=45, nullable=false)
     */
    private $matricula;

    /**
     * @var string
     *
     * @Column(name="Cpf", type="string", length=20, nullable=true)
     */
    private $cpf;

    /**
     * @var string
     *
     * @Column(name="Senha", type="string", length=256, nullable=true)
     */
    private $senha;

    /**
     * @var \Vwcurso
     *
     * @ManyToOne(targetEntity="Vwcurso")
     * @JoinColumns({
     *   @JoinColumn(name="Curso", referencedColumnName="Id")
     * })
     */
    private $curso;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return Vwaluno
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set matricula
     *
     * @param string $matricula
     *
     * @return Vwaluno
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * Get matricula
     *
     * @return string
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set cpf
     *
     * @param string $cpf
     *
     * @return Vwaluno
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set senha
     *
     * @param string $senha
     *
     * @return Vwaluno
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Get senha
     *
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set curso
     *
     * @param \Vwcurso $curso
     *
     * @return Vwaluno
     */
    public function setCurso(\Vwcurso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \Vwcurso
     */
    public function getCurso()
    {
        return $this->curso;
    }
}

