<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Vwservidor
 *
 * @ORM\Table(name="vwservidor")
 * @ORM\Entity
 */
class Vwservidor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nome", type="string", length=45, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="Matricula", type="string", length=45, nullable=false)
     */
    private $matricula;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Ativo", type="boolean", nullable=false)
     */
    private $ativo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Docente", type="boolean", nullable=false)
     */
    private $docente;

    /**
     * @var string
     *
     * @ORM\Column(name="Cargo", type="string", length=50, nullable=false)
     */
    private $cargo;


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
     * @return Vwservidor
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
     * @return Vwservidor
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
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return Vwservidor
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set docente
     *
     * @param boolean $docente
     *
     * @return Vwservidor
     */
    public function setDocente($docente)
    {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente
     *
     * @return boolean
     */
    public function getDocente()
    {
        return $this->docente;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     *
     * @return Vwservidor
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }
}

